<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_dashboard') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $revenue = Order::whereIn('status', ['delivered', 'shipping', 'completed'])->sum('total');
        $ordersCount = Order::count();
        $newUsers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $criticalStock = Product::whereBetween('stock', [1, 4])->whereNull('deleted_at')->count();
        $outOfStock = Product::where('stock', '<=', 0)->whereNull('deleted_at')->count();
        $latestOrders = Order::whereDate('created_at', today())->count();

        return [
            Stat::make('Total Revenue', number_format($revenue, 0, ',', '.') . ' ₫')
                ->description('Doanh thu toàn thời gian')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Total Orders', $ordersCount)
                ->description('Đơn hàng trọn đời')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('New Users This Month (' . now()->format('F Y') . ')', $newUsers)
                ->description('Đăng ký mới tháng ' . now()->format('n/Y'))
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Cảnh báo hết hàng (1-4)', $criticalStock . ' Sản phẩm')
                ->description('Nhắc Admin nhập hàng')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($criticalStock > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.products.index', ['tableFilters[stock_status][value]' => 'critical_stock'])),

            Stat::make('Sản phẩm hết hàng (=0)', $outOfStock . ' Sản phẩm')
                ->description('Đang mất doanh thu')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($outOfStock > 0 ? 'danger' : 'success')
                ->url(route('filament.admin.resources.products.index', ['tableFilters[stock_status][value]' => 'out_of_stock'])),

            Stat::make('Latest Orders', $latestOrders . ' New')
                ->description('Đơn hàng mới hôm nay')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
