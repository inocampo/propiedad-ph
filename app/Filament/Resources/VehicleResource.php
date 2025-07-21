<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Grouping\Group;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;
    protected static ?string $modelLabel = 'Vehículo';
    protected static ?string $pluralModelLabel = 'Vehículos';
    protected static ?string $navigationLabel = 'Vehículos';
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function getnavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('apartment_id')
                    ->relationship('apartment', 'number')
                    ->label('Apartamento')
                    ->searchable()
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->columnSpan(2),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'carro' => 'Carro',
                        'moto' => 'Moto',
                    ])
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('license_plate')
                    ->label('Placa')
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->columnSpan(1),
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->label('Marca')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre de la Marca')
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Esta marca ya ha sido registrada.',
                            ])
                            ->required(),
                    ])
                    ->required()
                    ->columnSpan(1),
                Forms\Components\Select::make('color_id')
                    ->relationship('color', 'name')
                    ->label('Color')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Color')
                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Este color ya ha sido registrado.',
                            ])
                            ->required(),
                    ])
                    ->required()
                    ->columnSpan(1),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('apartment.number')
            ->groupingSettingsHidden()
            ->groups([
                Group::make('apartment.number')
                    ->label('Apartamento')
                    ->collapsible(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('apartment.number')
                    ->label('Apartamento')
                    ->searchable()
                    ->sortable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Placa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color.name')
                    ->label('Color')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
