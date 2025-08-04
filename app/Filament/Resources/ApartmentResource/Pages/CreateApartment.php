<?php

namespace App\Filament\Resources\ApartmentResource\Pages;

use App\Filament\Resources\ApartmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApartment extends CreateRecord
{
    protected static string $resource = ApartmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Limpiar los campos temporales antes de crear
        unset($data['torre'], $data['piso'], $data['apto']);

        return $data;
    }
}