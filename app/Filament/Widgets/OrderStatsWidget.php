<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OrderStatsWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected function getStats(): array
    {
        $today = Carbon::today();
        
        return [
            Stat::make('Đơn mới hôm nay', Order::whereDate('created_at', $today)->count())
                ->description('Tổng số đơn phát sinh hôm nay')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),
            
            Stat::make('Doanh thu hôm nay', number_format(Order::whereDate('created_at', $today)->where('status', '!=', 'cancelled')->sum('total'), 0, ',', '.') . ' ₫')
                ->description('Số tiền dự kiến (trừ đơn hủy)')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Đang chờ xử lý', Order::where('status', 'pending')->count())
                ->description('Đơn hàng cần xác nhận')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Đang giao hàng', Order::where('status', 'shipping')->count())
                ->description('Đơn đang trên đường giao')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary'),
        ];
    }
}
