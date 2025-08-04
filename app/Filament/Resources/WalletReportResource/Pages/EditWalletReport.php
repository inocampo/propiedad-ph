<?php

namespace App\Filament\Resources\WalletReportResource\Pages;

use App\Filament\Resources\WalletReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWalletReport extends EditRecord
{
    protected static string $resource = WalletReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
