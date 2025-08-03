<?php

namespace App\Filament\Resources\ApartmentResource\Pages;

use App\Filament\Resources\ApartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApartments extends ListRecords
{
    protected static string $resource = ApartmentResource::class;

    protected static ?string $title = 'Apartamentos';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Apartamento'),
        ];
    }
}
