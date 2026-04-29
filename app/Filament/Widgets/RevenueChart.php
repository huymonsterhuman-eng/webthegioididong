<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        return 'Doanh thu năm ' . now()->year;
    }

    protected function getData(): array
    {
        $data = [];
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthStart = Carbon::now()->startOfYear()->addMonths($i - 1);
            $months[] = $monthStart->format('M');

            $revenue = Order::where('status', 'delivered')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', now()->year)
                ->sum('total');

            $data[] = $revenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
