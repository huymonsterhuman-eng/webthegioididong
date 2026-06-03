<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ListensToDashboardFilter;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Widget thống kê đơn hàng — 4 KPI nhanh.
 *
 * "Đơn mới" và "Doanh thu" được filter theo range Dashboard.
 * "Đang chờ xử lý" và "Đang giao" hiển thị realtime (không phụ thuộc range)
 * vì admin cần biết tình trạng pipeline hiện tại.
 */
class OrderStatsWidget extends BaseWidget
{
    use ListensToDashboardFilter;

    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected function getStats(): array
    {
        [$from, $to] = $this->dateRange();
        $rangeLabel  = $from->format('d/m') . ' → ' . $to->format('d/m');

        $newOrders = Order::whereBetween('created_at', [$from, $to])->count();
        $revenue   = Order::whereBetween('created_at', [$from, $to])
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        return [
            Stat::make('Đơn mới', $newOrders)
                ->description("Trong khoảng {$rangeLabel}")
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Doanh thu', number_format($revenue, 0, ',', '.') . ' ₫')
                ->description('Đã trừ đơn huỷ')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            // Realtime — không filter theo range
            Stat::make('Đang chờ xử lý', Order::where('status', 'pending')->count())
                ->description('Realtime — đơn cần xác nhận')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Đang giao hàng', Order::where('status', 'shipping')->count())
                ->description('Realtime — đơn trên đường giao')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),
        ];
    }
}
