<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceTypeResource\Pages;
use App\Models\InvoiceType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceTypeResource extends Resource
{
    protected static ?string $model = InvoiceType::class;
    protected static ?string $modelLabel = 'Tipo de Factura';
    protected static ?string $pluralModelLabel = 'Tipos de Factura';
    protected static ?string $navigationLabel = 'Tipos de Factura';
    protected static ?string $navigationGroup = 'Cartera';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Tipo de Factura')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('is_recurring')
                                    ->label('¿Es recurrente?')
                                    ->helperText('Si se marca, se generará automáticamente cada mes')
                                    ->default(false),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Monto Fijo')
                                    ->numeric()
                                    ->prefix('$')
                                    ->helperText('Dejar vacío si el monto es variable'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('¿Está activo?')
                                    ->default(true),
                            ]),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tipo de Factura')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record->description;
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Monto')
                    ->money('COP')
                    ->placeholder('Variable')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_recurring')
                    ->label('Recurrente')
                    ->boolean()
                    ->trueIcon('heroicon-o-arrow-path')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Activo')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark'),

                Tables\Columns\TextColumn::make('invoices_count')
                    ->label('Facturas')
                    ->counts('invoices')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/M/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),

                Tables\Filters\TernaryFilter::make('is_recurring')
                    ->label('Tipo')
                    ->placeholder('Todos')
                    ->trueLabel('Solo recurrentes')
                    ->falseLabel('Solo ocasionales'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activar seleccionados')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Desactivar seleccionados')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInvoiceTypes::route('/'),
        ];
    }
}