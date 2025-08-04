<?php

namespace App\Filament\Resources\PaymentResource\Widgets;

use Filament\Widgets\ChartWidget;

class MonthlyPaymentsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
