<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Apartment;
use App\Models\InvoiceType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Carbon\Carbon;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';
    protected static ?string $navigationLabel = 'Facturas';
    protected static ?string $navigationGroup = 'Cartera';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::pending()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Factura')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('apartment_id')
                                    ->relationship('apartment', 'number')
                                    ->label('Apartamento')
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(1),
                                
                                Forms\Components\Select::make('invoice_type_id')
                                    ->relationship('invoiceType', 'name')
                                    ->label('Tipo de Factura')
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $invoiceType = InvoiceType::find($state);
                                        if ($invoiceType && $invoiceType->amount) {
                                            $set('amount', $invoiceType->amount);
                                        }
                                    })
                                    ->columnSpan(1),
                                
                                Forms\Components\TextInput::make('number')
                                    ->label('Número de Factura')
                                    ->default(function () {
                                        $year = date('Y');
                                        $lastInvoice = Invoice::whereYear('created_at', $year)
                                            ->orderBy('id', 'desc')
                                            ->first();
                                        
                                        $nextNumber = $lastInvoice ? 
                                            intval(substr($lastInvoice->number, -3)) + 1 : 1;
                                        
                                        return 'INV-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                                    })
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->columnSpan(1),
                            ]),
                        
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('period')
                                    ->label('Período')
                                    ->placeholder('2025-01')
                                    ->default(date('Y-m'))
                                    ->required()
                                    ->columnSpan(1),
                                
                                Forms\Components\DatePicker::make('issue_date')
                                    ->label('Fecha de Emisión')
                                    ->default(now())
                                    ->required()
                                    ->columnSpan(1),
                                
                                Forms\Components\DatePicker::make('due_date')
                                    ->label('Fecha de Vencimiento')
                                    ->default(now()->addDays(15))
                                    ->required()
                                    ->columnSpan(1),
                                
                                Forms\Components\TextInput::make('amount')
                                    ->label('Valor')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                    ]),
                
                Forms\Components\Section::make('Detalles Adicionales')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'paid' => 'Pagado',
                                'overdue' => 'Vencido',
                                'cancelled' => 'Cancelado',
                            ])
                            ->default('pending')
                            ->required(),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(2),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->groups([
                Group::make('apartment.number')
                    ->label('Apartamento')
                    ->collapsible(),
                Group::make('period')
                    ->label('Período')
                    ->collapsible(),
                Group::make('status')
                    ->label('Estado')
                    ->collapsible(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('N° Factura')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('apartment.number')
                    ->label('Apartamento')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('apartment.resident_name')
                    ->label('Residente')
                    ->searchable()
                    ->limit(20),
                
                Tables\Columns\TextColumn::make('invoiceType.name')
                    ->label('Tipo')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('period')
                    ->label('Período')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('COP')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Vencimiento')
                    ->date('d/M/Y')
                    ->sortable()
                    ->color(fn (Invoice $record) => $record->is_overdue ? 'danger' : null),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'overdue' => 'Vencido',
                        'cancelled' => 'Cancelado',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/M/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado', 
                        'overdue' => 'Vencido',
                        'cancelled' => 'Cancelado',
                    ])
                    ->multiple(),
                
                SelectFilter::make('invoice_type_id')
                    ->label('Tipo de Factura')
                    ->relationship('invoiceType', 'name')
                    ->multiple(),
                
                Tables\Filters\Filter::make('overdue')
                    ->label('Solo Vencidas')
                    ->query(fn ($query) => $query->overdue()),
                
                Tables\Filters\Filter::make('current_month')
                    ->label('Mes Actual')
                    ->query(fn ($query) => $query->whereMonth('issue_date', now()->month)),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('mark_paid')
                        ->label('Marcar como Pagado')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Invoice $record) => $record->status === 'pending')
                        ->action(fn (Invoice $record) => $record->markAsPaid())
                        ->requiresConfirmation(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_overdue')
                        ->label('Marcar como Vencidas')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function (Invoice $invoice) {
                                if ($invoice->status === 'pending' && $invoice->due_date < now()) {
                                    $invoice->update(['status' => 'overdue']);
                                }
                            });
                        })
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}