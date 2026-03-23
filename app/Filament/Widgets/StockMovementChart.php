<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\GoodsReceiptDetail;
use App\Models\OrderDetail;
use Carbon\Carbon;

class StockMovementChart extends ChartWidget
{
    protected static ?string $heading = 'Biến động Nhập - Xuất (6 tháng qua)';
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return auth()->user()->can('view_dashboard') || auth()->user()->hasRole('super-admin');
    }

    protected function getData(): array
    {
        $months = [];
        $incoming = [];
        $outgoing = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('m/Y');

            $in = GoodsReceiptDetail::whereHas('goodsReceipt', function($q) use ($date) {
                $q->whereYear('created_at', $date->year)
                  ->whereMonth('created_at', $date->month);
            })->sum('quantity');

            $out = OrderDetail::whereHas('order', function($q) use ($date) {
                $q->whereYear('created_at', $date->year)
                  ->whereMonth('created_at', $date->month)
                  ->where('status', '!=', 'cancelled');
            })->sum('quantity');

            $incoming[] = (int) $in;
            $outgoing[] = (int) $out;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Hàng Nhập (Phiếu nhập)',
                    'data' => $incoming,
                    'backgroundColor' => '#3b82f6', // blue-500
                ],
                [
                    'label' => 'Hàng Bán (Đơn hàng)',
                    'data' => $outgoing,
                    'backgroundColor' => '#eab308', // yellow-500
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
