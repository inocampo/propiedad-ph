<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $modelLabel = 'Pago';
    protected static ?string $pluralModelLabel = 'Pagos';
    protected static ?string $navigationLabel = 'Pagos';
    protected static ?string $navigationGroup = 'Cartera';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Pago')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('invoice_id')
                                    ->label('Factura')
                                    ->relationship('invoice', 'number')
                                    ->searchable()
                                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                                        "{$record->number} - Apto {$record->apartment->number} - $" . number_format($record->amount, 0))
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state) {
                                            $invoice = Invoice::find($state);
                                            $set('apartment_id', $invoice->apartment_id);
                                            $set('amount', $invoice->balance);
                                        }
                                    })
                                    ->required(),
                                
                                Forms\Components\Select::make('apartment_id')
                                    ->relationship('apartment', 'number')
                                    ->label('Apartamento')
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                        
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->label('Valor del Pago')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                
                                Forms\Components\DatePicker::make('payment_date')
                                    ->label('Fecha de Pago')
                                    ->default(now())
                                    ->required(),
                                
                                Forms\Components\Select::make('payment_method_id')
                                    ->relationship('paymentMethod', 'name')
                                    ->label('Método de Pago')
                                    ->required()
                                    ->live(),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('reference_number')
                                    ->label('Número de Referencia')
                                    ->visible(function ($get) {
                                        $paymentMethodId = $get('payment_method_id');
                                        if ($paymentMethodId) {
                                            $paymentMethod = \App\Models\PaymentMethod::find($paymentMethodId);
                                            return $paymentMethod?->requires_reference;
                                        }
                                        return false;
                                    }),
                                
                                Forms\Components\Hidden::make('received_by')
                                    ->default(auth()->id()),
                            ]),
                    ]),
                
                Forms\Components\Section::make('Notas Adicionales')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('payment_date', 'desc')
            ->groups([
                Group::make('apartment.number')
                    ->label('Apartamento')
                    ->collapsible(),
                Group::make('payment_date')
                    ->label('Fecha')
                    ->date()
                    ->collapsible(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Fecha')
                    ->date('d/M/Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('apartment.number')
                    ->label('Apto')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('apartment.resident_name')
                    ->label('Residente')
                    ->searchable()
                    ->limit(25),
                
                Tables\Columns\TextColumn::make('invoice.number')
                    ->label('Factura')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('invoice.invoiceType.name')
                    ->label('Tipo')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('COP')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->label('Método')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Referencia')
                    ->placeholder('N/A')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('receivedByUser.name')
                    ->label('Recibido por')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('payment_method_id')
                    ->label('Método de Pago')
                    ->relationship('paymentMethod', 'name')
                    ->multiple(),
                
                Tables\Filters\Filter::make('today')
                    ->label('Hoy')
                    ->query(fn ($query) => $query->whereDate('payment_date', today())),
                
                Tables\Filters\Filter::make('this_month')
                    ->label('Este Mes')
                    ->query(fn ($query) => $query->whereMonth('payment_date', now()->month)),
                
                Tables\Filters\Filter::make('amount_range')
                    ->form([
                        Forms\Components\TextInput::make('amount_from')
                            ->label('Valor desde')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('amount_to')
                            ->label('Valor hasta')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['amount_from'], fn ($q) => $q->where('amount', '>=', $data['amount_from']))
                            ->when($data['amount_to'], fn ($q) => $q->where('amount', '<=', $data['amount_to']));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}