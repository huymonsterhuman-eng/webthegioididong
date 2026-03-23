<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrdersByStatusChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_dashboard') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 1;

    protected static ?string $heading = 'Orders by Status';

    protected function getData(): array
    {
        $statuses = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $labels = [];
        $data = [];
        $colors = [];

        $statusMap = [
            'pending' => ['label' => 'Pending', 'color' => '#f59e0b'],
            'confirmed' => ['label' => 'Confirmed', 'color' => '#3b82f6'],
            'shipping' => ['label' => 'Shipping', 'color' => '#8b5cf6'],
            'delivered' => ['label' => 'Delivered', 'color' => '#10b981'],
            'cancelled' => ['label' => 'Cancelled', 'color' => '#ef4444'],
        ];

        foreach ($statuses as $status => $count) {
            $labels[] = isset($statusMap[$status]) ? $statusMap[$status]['label'] : ucfirst($status);
            $data[] = $count;
            $colors[] = isset($statusMap[$status]) ? $statusMap[$status]['color'] : '#6b7280';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
