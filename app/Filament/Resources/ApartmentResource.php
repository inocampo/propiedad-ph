<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApartmentResource\Pages;
use App\Models\Apartment;
use Illuminate\Support\HtmlString;
use App\Models\Brand;
use App\Models\Breed;
use App\Models\Color;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;

class ApartmentResource extends Resource
{
    protected static ?string $model = Apartment::class;
    protected static ?string $modelLabel = 'Apartamento';
    protected static ?string $pluralModelLabel = 'Apartamentos';
    protected static ?string $navigationLabel = 'Apartamentos';
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('General')->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Section::make('Información Principal')
                                ->schema([
                                    Forms\Components\Grid::make(4)
                                        ->schema([
                                            Forms\Components\Select::make('torre')
                                                ->label('Torre')
                                                ->options(array_combine(range('A', 'M'), range('A', 'M')))
                                                ->required(fn (string $operation): bool => $operation === 'create')
                                                ->disabled(fn (string $operation): bool => $operation === 'edit')
                                                ->live()
                                                ->afterStateUpdated(function ($set, $state, $get) use ($form) {
                                                    // Solo actualizar en creación
                                                    if ($form->getOperation() === 'create') {
                                                        $torre = $state ?? '';
                                                        $piso = $get('piso') ?? '';
                                                        $apto = $get('apto') ?? '';
                                                        $set('number', $torre . $piso . $apto);
                                                    }
                                                }),
                                            Forms\Components\Select::make('piso')
                                                ->label('Piso')
                                                ->options([
                                                    '1' => '1',
                                                    '2' => '2',
                                                    '3' => '3',
                                                    '4' => '4',
                                                    '5' => '5',
                                                ])
                                                ->required(fn (string $operation): bool => $operation === 'create')
                                                ->disabled(fn (string $operation): bool => $operation === 'edit')
                                                ->live()
                                                ->afterStateUpdated(function ($set, $state, $get) use ($form) {
                                                    // Solo actualizar en creación
                                                    if ($form->getOperation() === 'create') {
                                                        $torre = $get('torre') ?? '';
                                                        $piso = $state ?? '';
                                                        $apto = $get('apto') ?? '';
                                                        $set('number', $torre . $piso . $apto);
                                                    }
                                                }),
                                            Forms\Components\Select::make('apto')
                                                ->label('Apto')
                                                ->options([
                                                    '01' => '01',
                                                    '02' => '02',
                                                    '03' => '03',
                                                    '04' => '04',
                                                ])
                                                ->required(fn (string $operation): bool => $operation === 'create')
                                                ->disabled(fn (string $operation): bool => $operation === 'edit')
                                                ->live()
                                                ->afterStateUpdated(function ($set, $state, $get) use ($form) {
                                                    // Solo actualizar en creación
                                                    if ($form->getOperation() === 'create') {
                                                        $torre = $get('torre') ?? '';
                                                        $piso = $get('piso') ?? '';
                                                        $apto = $state ?? '';
                                                        $set('number', $torre . $piso . $apto);
                                                    }
                                                }),
                                            Forms\Components\TextInput::make('number')
                                                ->label('Código Apartamento')
                                                ->disabled()
                                                ->dehydrated(),
                                        ]),
                                    Forms\Components\Section::make('Datos del Residente Principal')
                                        ->description('Quien figurará como responsable del apartamento.')
                                        ->schema([
                                            Forms\Components\Grid::make(6)->schema([
                                                Forms\Components\TextInput::make('resident_name')
                                                ->label('Nombre')
                                                ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                                ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                                                ->required()
                                                ->columnSpan(2),
                                            Forms\Components\TextInput::make('resident_document')
                                                ->label('Cédula')
                                                ->required()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('resident_phone')
                                                ->label('Celular')
                                                ->tel()
                                                ->required()
                                                ->columnSpan(1),
                                            Forms\Components\TextInput::make('resident_email')
                                                ->label('Email')
                                                ->email()
                                                ->required()
                                                ->dehydrateStateUsing(fn ($state) => strtolower($state))
                                                ->extraInputAttributes(['style' => 'text-transform: lowercase'])
                                                ->columnSpan(2),
                                            ])
                                        ])
                                ])->columns(4),
                            Forms\Components\Section::make('Información Adicional')
                                ->schema([
                                    Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Checkbox::make('received_manual')
                                        ->label('Recibí el manual de convivencia.')
                                        ->columnSpan(1),
                                        Forms\Components\Checkbox::make('has_bicycles')
                                            ->label('¿Tienen bicicletas?')
                                            ->live()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('bicycles_count')
                                            ->label('Cantidad de bicicletas')
                                            ->numeric()
                                            ->minValue(1)
                                            ->required()
                                            ->visible(fn ($get) => $get('has_bicycles'))
                                            ->columnSpan(1),
                                    ]), 
                                    Forms\Components\Textarea::make('observations')
                                        ->label('Observaciones')
                                        ->columnSpanFull(),
                                ])->columns(2),
                        ]),
                    Wizard\Step::make('Propietarios')
                        ->label(fn ($get) => new HtmlString(sprintf('Propietarios <span class="ml-2 rounded-md bg-green-500/10 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-500/20">%d</span>', count($get('owners') ?? []))))
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            Forms\Components\Repeater::make('owners')
                                ->relationship()
                                ->label('Propietarios')
                                ->default(fn ($record) => $record ? $record->owners : [])
                                ->addAction(fn (Action $action) => $action->label('Agregar Propietario')->icon('heroicon-o-user-plus')->color('success'))
                                ->helperText('Si no hay propietarios, haz clic en el botón para añadir el primero.')
                                ->schema([
                                    Forms\Components\TextInput::make('name')->label('Nombre')->dehydrateStateUsing(fn ($state) => strtoupper($state))->extraInputAttributes(['style' => 'text-transform: uppercase'])->required()->columnSpan(3),
                                    Forms\Components\TextInput::make('document_number')->label('Cédula')->required()->columnSpan(2),
                                    Forms\Components\TextInput::make('phone_number')->label('Celular')->tel()->required()->columnSpan(2),
                                    Forms\Components\TextInput::make('email')->label('Email')->email()->dehydrateStateUsing(fn ($state) => strtolower($state))->extraInputAttributes(['style' => 'text-transform: lowercase'])->nullable()->columnSpan(3),
                                ])
                                ->columns(10)
                                ->live(),
                        ]),
                    Wizard\Step::make('Residentes')
                    ->label(fn ($get) => new HtmlString(sprintf('Residentes <span class="ml-2 rounded-md bg-blue-500/10 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-500/20">%d</span>', count($get('residents') ?? []))))
                    ->icon('heroicon-o-users')
                    ->schema([
                        // ✅ Sección con botones de acción
                        Forms\Components\Section::make('Opciones para agregar residentes')
                            ->schema([
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('select_owners')
                                        ->label('Seleccionar Propietarios como Residentes')
                                        ->icon('heroicon-o-user-plus')
                                        ->color('info')
                                        ->form(fn (callable $get) => [
                                            Forms\Components\Section::make('Selecciona los propietarios a agregar como residentes')
                                                ->schema([
                                                    Forms\Components\CheckboxList::make('selected_owners')
                                                        ->label('Propietarios disponibles')
                                                        ->options(function () use ($get) {
                                                            $owners = $get('owners') ?? [];
                                                            return collect($owners)->mapWithKeys(function ($owner, $index) {
                                                                return [$index => ($owner['name'] ?? 'Sin nombre') . ' - ' . ($owner['document_number'] ?? 'Sin cédula')];
                                                            })->toArray();
                                                        })
                                                        ->columns(1)
                                                        ->required()
                                                        ->helperText('Selecciona los propietarios que también son residentes'),
                                                ])
                                        ])
                                        ->action(function (array $data, callable $get, callable $set) {
                                            $owners = $get('owners') ?? [];
                                            $selected = collect($data['selected_owners'] ?? []);
                                            
                                            $newResidents = $selected->map(fn ($index) => [
                                                'name' => $owners[$index]['name'] ?? '',
                                                'document_number' => $owners[$index]['document_number'] ?? '',
                                                'phone_number' => $owners[$index]['phone_number'] ?? '',
                                                'relationship_id' => null,
                                                'phone_for_intercom' => true,
                                            ])->toArray();
                
                                            $currentResidents = $get('residents') ?? [];
                                            $set('residents', array_merge($currentResidents, $newResidents));
                                        })
                                        ->visible(fn (callable $get) => count($get('owners') ?? []) > 0),
                                ]),
                            ])
                            ->collapsible()
                            ->collapsed(false)
                            ->visible(fn (callable $get) => count($get('owners') ?? []) > 0),
                        
                        // ✅ Repeater normal con su botón de agregar
                        Forms\Components\Repeater::make('residents')
                            ->relationship()
                            ->label('Lista de Residentes')
                            ->default(fn ($record) => $record ? $record->residents : [])
                            ->addAction(
                                fn (Action $action) => $action
                                    ->label('Agregar Residente')
                                    ->icon('heroicon-o-user-plus')
                                    ->color('success')
                            )
                            ->helperText('Lista de todos los residentes del apartamento. Puedes agregar residentes manualmente o usar el botón de arriba para seleccionar propietarios.')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nombre Completo')
                                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                    ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                                    ->required()
                                    ->columnSpan(2),
                                    
                                Forms\Components\Select::make('relationship_id')
                                    ->relationship('relationship', 'name')
                                    ->label('Parentesco')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Parentesco')
                                            ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                            ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('name', strtoupper($state)))
                                            ->unique(ignoreRecord: true)
                                            ->validationMessages([
                                                'unique' => 'Este parentesco ya ha sido registrado.',
                                            ])
                                            ->required(),
                                    ])
                                    ->required()
                                    ->columnSpan(2),
                                    
                                Forms\Components\TextInput::make('document_number')
                                    ->label('Cédula')
                                    ->required()
                                    ->columnSpan(2),
                                    
                                Forms\Components\TextInput::make('phone_number')
                                    ->label('Celular')
                                    ->tel()
                                    ->required()
                                    ->columnSpan(2),
                                    
                                Forms\Components\Checkbox::make('phone_for_intercom')
                                    ->label('Citófono')
                                    ->helperText('Autoriza usar este celular en la citofonía')
                                    ->columnSpan(2),
                            ])
                            ->columns(10)
                            ->live(),
                    ]),
                    Wizard\Step::make('Menores de Edad')
                        ->label(fn ($get) => new HtmlString(sprintf('Menores <span class="ml-2 rounded-md bg-blue-500/10 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-500/20">%d</span>', count($get('minors') ?? []))))
                        ->icon('heroicon-o-face-smile')
                        ->schema([
                            Forms\Components\Repeater::make('minors')
                                ->relationship()
                                ->label('Menores de Edad a Cargo')
                                ->default(fn ($record) => $record ? $record->minors : [])
                                ->addAction(fn (Action $action) => $action->label('Agregar Menor de Edad')->icon('heroicon-o-face-smile')->color('success'))
                                ->helperText('Si no hay menores, haz clic en el botón para añadir el primero.')
                                ->schema([
                                    Forms\Components\TextInput::make('name')->label('Nombre')->dehydrateStateUsing(fn ($state) => strtoupper($state))->extraInputAttributes(['style' => 'text-transform: uppercase'])->required()->columnSpan(1),
                                    Forms\Components\TextInput::make('age')->label('Edad')->numeric()->required()->columnSpan(1),
                                    Forms\Components\Select::make('gender')
                                        ->label('Género')
                                        ->options([
                                            'niño' => 'Niño',
                                            'niña' => 'Niña',
                                        ])
                                        ->required()
                                        ->columnSpan(1),
                                ])
                                ->columns(3)
                                ->live(),
                        ]),
                    Wizard\Step::make('Vehículos')
                        ->label(fn ($get) => new HtmlString(sprintf('Vehículos <span class="ml-2 rounded-md bg-blue-500/10 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-500/20">%d</span>', count($get('vehicles') ?? []))))
                        ->icon('heroicon-o-truck')
                        ->schema([
                            Forms\Components\Repeater::make('vehicles')
                                ->relationship()
                                ->label('Vehículos del Apartamento')
                                ->default(fn ($record) => $record ? $record->vehicles : [])
                                ->addAction(fn (Action $action) => $action->label('Agregar Vehículo')->icon('heroicon-o-truck')->color('success'))
                                ->helperText('Si no hay vehículos, haz clic en el botón para añadir el primero.')
                                ->schema([
                                    Forms\Components\Select::make('type')
                                        ->label('Tipo')
                                        ->options([
                                            'carro' => 'Carro',
                                            'moto' => 'Moto',
                                        ])
                                        ->required()
                                        ->columnSpan(2),
                                    Forms\Components\TextInput::make('license_plate')
                                        ->label('Placa')
                                        ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                        ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                                        ->required()
                                        ->columnSpan(2),
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
                                        ->columnSpan(3),
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
                                        ->columnSpan(3),
                                ])
                            ->columns(10)
                            ->live(),
                        ]),
                    Wizard\Step::make('Mascotas')
                        ->label(fn ($get) => new HtmlString(sprintf('Mascotas <span class="ml-2 rounded-md bg-blue-500/10 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-500/20">%d</span>', count($get('pets') ?? []))))
                        ->icon('heroicon-o-heart')
                        ->schema([
                            Forms\Components\Repeater::make('pets')
                                ->relationship()
                                ->label('Mascotas del Apartamento')
                                ->default(fn ($record) => $record ? $record->pets : [])
                                ->addAction(fn (Action $action) => $action->label('Agregar Mascota')->icon('heroicon-o-heart')->color('success'))
                                ->helperText('Si no hay mascotas, haz clic en el botón para añadir el primero.')
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nombre')
                                        ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                        ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                                        ->required()
                                        ->columnSpan(3),
                                    Forms\Components\Select::make('type')
                                        ->label('Tipo')
                                        ->options([
                                            'perro' => 'Perro',
                                            'gato' => 'Gato',
                                        ])
                                        ->required()
                                        ->columnSpan(2),
                                    Forms\Components\Select::make('breed_id')
                                        ->relationship('breed', 'name')
                                        ->label('Raza')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('name')
                                                ->label('Nombre de la Raza')
                                                ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                                                ->extraInputAttributes(['style' => 'text-transform: uppercase'])
                                                ->unique(ignoreRecord: true)
                                                ->validationMessages([
                                                    'unique' => 'Esta raza ya ha sido registrada.',
                                                ])
                                                ->required(),
                                        ])
                                        ->required()
                                        ->columnSpan(3),
                                ])
                            ->columns(8)
                            ->live(),
                        ]),
                ])
                ->columnSpanFull()
                ->contained(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Apto')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resident_name')
                    ->label('Propietario Principal')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('resident_phone')
                    ->label('Celular'),
                Tables\Columns\TextColumn::make('resident_email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('has_bicycles')
                    ->label('Bicicletas')
                    ->boolean(),
                Tables\Columns\IconColumn::make('received_manual')
                    ->label('Manual')
                    ->boolean(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApartments::route('/'),
            'create' => Pages\CreateApartment::route('/create'),
            'edit' => Pages\EditApartment::route('/{record}/edit'),
        ];
    }
}