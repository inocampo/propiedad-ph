<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ColorResource\Pages;
use App\Filament\Resources\ColorResource\RelationManagers;
use App\Models\Color;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ColorResource extends Resource
{
    protected static ?string $modelLabel = 'Color';
    protected static ?string $pluralModelLabel = 'Colores';
    protected static ?string $navigationLabel = 'Colores';
    protected static ?string $navigationGroup = 'Catálogos';
    protected static ?string $model = Color::class;
    protected static ?string $navigationIcon = 'heroicon-o-eye-dropper';

    public static function getnavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Color')
                    ->required()
                    ->autofocus()
                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                    ->afterStateUpdated(fn ($state, callable $set) => $set('name', strtoupper($state)))
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Este color ya ha sido registrado.',
                    ])
                    ->extraAttributes([
                        'x-on:focus-name-field.window' => '$el.focus(); $el.select();',
                        'x-on:created-record.window' => 'setTimeout(() => { $el.focus(); $el.select(); }, 200)'
                    ])
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->modalHeading('Crear Color')
                ->modalDescription('Por favor, introduce el nombre del color para crearlo.')
                ->modalIcon('heroicon-o-eye-dropper')
                ->modalWidth('xl')
                ->createAnother(true)
                ->after(function ($livewire) {
                    // Disparar evento personalizado y también intentar con JavaScript directo
                    $livewire->dispatch('created-record');
                    $livewire->js('
                        setTimeout(() => {
                            // Intentar varios selectores para encontrar el campo
                            let nameInput = document.querySelector(\'[name="name"]\') ||
                                          document.querySelector(\'[wire\\\\:model="data.name"]\') ||
                                          document.querySelector(\'input[placeholder*="Color"]\') ||
                                          document.querySelector(\'.fi-fo-text-input input\');
                            
                            if (nameInput) {
                                nameInput.focus();
                                nameInput.select();
                                console.log("Campo enfocado correctamente");
                            } else {
                                console.log("No se encontró el campo name");
                            }
                        }, 500);
                    ');
                }),
        ])
            ->defaultSort('name', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Color')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageColors::route('/'),
        ];
    }
}