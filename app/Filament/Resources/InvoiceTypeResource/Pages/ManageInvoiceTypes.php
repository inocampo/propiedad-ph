<?php

namespace App\Filament\Resources\InvoiceTypeResource\Pages;

use App\Filament\Resources\InvoiceTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoiceTypes extends ManageRecords
{
    protected static string $resource = InvoiceTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
