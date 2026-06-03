<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ListensToDashboardFilter;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Widget thống kê chính (KPI).
 *
 * Các stat về Doanh thu / Đơn hàng / User mới / Đơn mới hôm nay được filter
 * theo range chọn ở DashboardFilterForm. Stat tồn kho (critical / out-of-stock)
 * luôn realtime, không phụ thuộc range.
 */
class StatsOverview extends BaseWidget
{
    use ListensToDashboardFilter;

    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        [$from, $to] = $this->dateRange();
        $rangeLabel  = $from->format('d/m') . ' → ' . $to->format('d/m');

        // Doanh thu trong khoảng (chỉ tính đơn không bị huỷ)
        $revenue = Order::whereIn('status', ['delivered', 'shipping', 'completed'])
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');

        // Tổng đơn hàng trong khoảng
        $ordersCount = Order::whereBetween('created_at', [$from, $to])->count();

        // User mới đăng ký trong khoảng
        $newUsers = User::whereBetween('created_at', [$from, $to])->count();

        // Đơn mới trong khoảng
        $rangeOrders = Order::whereBetween('created_at', [$from, $to])->count();

        // Stat tồn kho realtime — không phụ thuộc filter
        $criticalStock = Product::whereBetween('stock', [1, 4])->whereNull('deleted_at')->count();
        $outOfStock    = Product::where('stock', '<=', 0)->whereNull('deleted_at')->count();

        return [
            Stat::make('Doanh thu', number_format($revenue, 0, ',', '.') . ' ₫')
                ->description("Trong khoảng {$rangeLabel}")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Tổng đơn hàng', $ordersCount)
                ->description("Đơn trong khoảng {$rangeLabel}")
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('User mới', $newUsers)
                ->description("Đăng ký trong khoảng {$rangeLabel}")
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Cảnh báo hết hàng (1-4)', $criticalStock . ' SP')
                ->description('Realtime — nhắc nhập hàng')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($criticalStock > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.products.index', ['tableFilters[stock_status][value]' => 'critical_stock'])),

            Stat::make('Sản phẩm hết hàng (=0)', $outOfStock . ' SP')
                ->description('Realtime — đang mất doanh thu')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($outOfStock > 0 ? 'danger' : 'success')
                ->url(route('filament.admin.resources.products.index', ['tableFilters[stock_status][value]' => 'out_of_stock'])),

            Stat::make('Đơn mới trong khoảng', $rangeOrders . ' đơn')
                ->description("Khoảng {$rangeLabel}")
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
