<?php

namespace App\Filament\Resources\WalletReportResource\Pages;

use App\Filament\Resources\WalletReportResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListWalletReports extends ListRecords
{
    protected static string $resource = WalletReportResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('dashboard')
                ->label('Ver Dashboard')
                ->icon('heroicon-o-chart-pie')
                ->color('info')
                ->url(route('filament.admin.pages.dashboard')),
        ];
    }
}