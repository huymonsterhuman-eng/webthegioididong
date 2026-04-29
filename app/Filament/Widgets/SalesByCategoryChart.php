<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class SalesByCategoryChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 1;

    protected static ?string $heading = 'Doanh số theo danh mục';

    protected function getData(): array
    {
        $sales = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(order_details.quantity) as total_sold'))
            ->groupBy('categories.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $labels = $sales->pluck('name')->toArray();
        $data = $sales->pluck('total_sold')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Products Sold',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#8b5cf6',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
