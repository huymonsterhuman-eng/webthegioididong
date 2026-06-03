<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ListensToDashboardFilter;
use App\Models\GoodsReceiptDetail;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

/**
 * Biểu đồ biến động nhập/xuất kho theo thời gian.
 *
 * Smart bucket dựa khoảng thời gian (giống RevenueChart):
 *   - ≤ 31 ngày  → group theo ngày
 *   - ≤ 180 ngày → group theo tuần
 *   - > 180 ngày → group theo tháng
 */
class StockMovementChart extends ChartWidget
{
    use ListensToDashboardFilter;

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    public function getHeading(): string
    {
        [$from, $to] = $this->dateRange();
        return 'Biến động Nhập - Xuất (' . $from->format('d/m') . ' → ' . $to->format('d/m') . ')';
    }

    protected function getData(): array
    {
        [$from, $to] = $this->dateRange();
        $totalDays = (int) $from->diffInDays($to) + 1;

        $bucket = $totalDays <= 31 ? 'day' : ($totalDays <= 180 ? 'week' : 'month');

        $labels   = [];
        $incoming = [];
        $outgoing = [];
        $cursor   = $from->copy();

        while ($cursor->lte($to)) {
            if ($bucket === 'day') {
                $bs = $cursor->copy()->startOfDay();
                $be = $cursor->copy()->endOfDay();
                $label = $cursor->format('d/m');
                $next = $cursor->copy()->addDay();
            } elseif ($bucket === 'week') {
                $bs = $cursor->copy()->startOfWeek();
                $be = $cursor->copy()->endOfWeek();
                if ($bs->lt($from)) $bs = $from->copy();
                if ($be->gt($to))   $be = $to->copy();
                $label = 'Tuần ' . $bs->format('d/m');
                $next = $cursor->copy()->next('Monday');
            } else {
                $bs = $cursor->copy()->startOfMonth();
                $be = $cursor->copy()->endOfMonth();
                if ($bs->lt($from)) $bs = $from->copy();
                if ($be->gt($to))   $be = $to->copy();
                $label = $cursor->format('m/Y');
                $next = $cursor->copy()->addMonthNoOverflow()->startOfMonth();
            }

            $in = GoodsReceiptDetail::whereHas('goodsReceipt', function ($q) use ($bs, $be) {
                $q->whereBetween('created_at', [$bs, $be]);
            })->sum('quantity');

            $out = OrderDetail::whereHas('order', function ($q) use ($bs, $be) {
                $q->whereBetween('created_at', [$bs, $be])
                    ->where('status', '!=', 'cancelled');
            })->sum('quantity');

            $labels[]   = $label;
            $incoming[] = (int) $in;
            $outgoing[] = (int) $out;

            $cursor = $next;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Hàng Nhập (Phiếu nhập)',
                    'data'            => $incoming,
                    'backgroundColor' => '#3b82f6',
                ],
                [
                    'label'           => 'Hàng Bán (Đơn hàng)',
                    'data'            => $outgoing,
                    'backgroundColor' => '#eab308',
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
