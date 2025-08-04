<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletReportResource\Pages;
use App\Models\Apartment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WalletReportResource extends Resource
{
    protected static ?string $model = Apartment::class;
    protected static ?string $modelLabel = 'Reporte de Cartera';
    protected static ?string $pluralModelLabel = 'Reportes de Cartera';
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $navigationGroup = 'Cartera';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->query(Apartment::query())
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Apartamento')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('resident_name')
                    ->label('Residente')
                    ->searchable()
                    ->limit(25),
                
                Tables\Columns\TextColumn::make('resident_phone')
                    ->label('Teléfono')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('total_invoiced')
                    ->label('Total Facturado')
                    ->state(function (Apartment $record) {
                        return $record->invoices()->sum('amount');
                    })
                    ->money('COP')
                    ->sortable(false),
                
                Tables\Columns\TextColumn::make('total_paid')
                    ->label('Total Pagado')
                    ->state(function (Apartment $record) {
                        return $record->payments()->sum('amount');
                    })
                    ->money('COP')
                    ->sortable(false),
                
                Tables\Columns\TextColumn::make('balance')
                    ->label('Saldo Pendiente')
                    ->state(function (Apartment $record) {
                        $invoiced = $record->invoices()->sum('amount');
                        $paid = $record->payments()->sum('amount');
                        return $invoiced - $paid;
                    })
                    ->money('COP')
                    ->color(function ($state) {
                        return $state > 0 ? 'danger' : 'success';
                    })
                    ->sortable(false),
                
                Tables\Columns\TextColumn::make('pending_invoices')
                    ->label('Fact. Pendientes')
                    ->state(function (Apartment $record) {
                        return $record->invoices()->where('status', 'pending')->count();
                    })
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),
                
                Tables\Columns\TextColumn::make('overdue_invoices')
                    ->label('Fact. Vencidas')
                    ->state(function (Apartment $record) {
                        return $record->invoices()->where('status', 'overdue')->count();
                    })
                    ->alignCenter()
                    ->badge()
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('payment_score')
                    ->label('% Cumplimiento')
                    ->state(function (Apartment $record) {
                        $invoiced = $record->invoices()->sum('amount');
                        $paid = $record->payments()->sum('amount');
                        
                        if ($invoiced == 0) return 100;
                        
                        return round(($paid / $invoiced) * 100, 1);
                    })
                    ->suffix('%')
                    ->color(function ($state) {
                        return $state >= 90 ? 'success' : 
                               ($state >= 70 ? 'warning' : 'danger');
                    })
                    ->sortable(false),
            ])
            ->filters([
                Tables\Filters\Filter::make('with_balance')
                    ->label('Solo con Saldo Pendiente')
                    ->query(function (Builder $query) {
                        return $query->whereHas('invoices', function ($q) {
                            $q->where('status', '!=', 'paid');
                        });
                    }),
                
                Tables\Filters\Filter::make('with_overdue')
                    ->label('Con Facturas Vencidas')
                    ->query(function (Builder $query) {
                        return $query->whereHas('invoices', function ($q) {
                            $q->where('status', 'overdue');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('view_detail')
                        ->label('Ver Detalle')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->url(fn (Apartment $record) => 
                            route('filament.admin.resources.apartments.edit', $record)),
                    
                    Tables\Actions\Action::make('whatsapp_reminder')
                        ->label('Recordatorio WhatsApp')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->color('success')
                        ->visible(function (Apartment $record) {
                            $invoiced = $record->invoices()->sum('amount');
                            $paid = $record->payments()->sum('amount');
                            return ($invoiced - $paid) > 0;
                        })
                        ->url(function (Apartment $record) {
                            $invoiced = $record->invoices()->sum('amount');
                            $paid = $record->payments()->sum('amount');
                            $balance = $invoiced - $paid;
                            $message = "Hola {$record->resident_name}, tu saldo pendiente es de $" . number_format($balance, 0) . ". Por favor ponerte al día con tus pagos.";
                            
                            return 'https://wa.me/57' . $record->resident_phone . '?text=' . urlencode($message);
                        })
                        ->openUrlInNewTab(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_excel')
                    ->label('Exportar a Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        // TODO: Implementar exportación
                    }),
            ])
            ->defaultSort('number', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWalletReports::route('/'),
        ];
    }
}