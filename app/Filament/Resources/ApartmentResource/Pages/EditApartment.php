<?php

namespace App\Filament\Resources\ApartmentResource\Pages;

use App\Filament\Resources\ApartmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApartment extends EditRecord
{
    protected static string $resource = ApartmentResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Extraer torre, piso y apto del número de apartamento
        if (isset($data['number']) && $data['number']) {
            $data['torre'] = substr($data['number'], 0, 1);
            $data['piso'] = substr($data['number'], 1, 1);
            $data['apto'] = substr($data['number'], 2, 2);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // En edición, mantenemos el número original ya que los campos están deshabilitados
        // Solo limpiamos los campos temporales
        unset($data['torre'], $data['piso'], $data['apto']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->iconButton(),
        ];
    }
}