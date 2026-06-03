<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ListensToDashboardFilter;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

/**
 * Biểu đồ doanh thu theo thời gian.
 *
 * Smart bucket dựa khoảng thời gian:
 *   - ≤ 31 ngày  → group theo ngày (d/m)
 *   - ≤ 180 ngày → group theo tuần (Tuần n)
 *   - > 180 ngày → group theo tháng (m/Y)
 */
class RevenueChart extends ChartWidget
{
    use ListensToDashboardFilter;

    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        [$from, $to] = $this->dateRange();
        return 'Doanh thu ' . $from->format('d/m/Y') . ' → ' . $to->format('d/m/Y');
    }

    protected function getData(): array
    {
        [$from, $to] = $this->dateRange();
        $totalDays = (int) $from->diffInDays($to) + 1;

        // Chọn cách bucket dựa khoảng ngày
        if ($totalDays <= 31) {
            $bucket = 'day';
        } elseif ($totalDays <= 180) {
            $bucket = 'week';
        } else {
            $bucket = 'month';
        }

        $labels  = [];
        $data    = [];
        $cursor  = $from->copy();

        while ($cursor->lte($to)) {
            // Xác định biên đầu / cuối bucket
            if ($bucket === 'day') {
                $bucketStart = $cursor->copy()->startOfDay();
                $bucketEnd   = $cursor->copy()->endOfDay();
                $label       = $cursor->format('d/m');
                $next        = $cursor->copy()->addDay();
            } elseif ($bucket === 'week') {
                $bucketStart = $cursor->copy()->startOfWeek();
                $bucketEnd   = $cursor->copy()->endOfWeek();
                if ($bucketStart->lt($from)) $bucketStart = $from->copy();
                if ($bucketEnd->gt($to))     $bucketEnd   = $to->copy();
                $label       = 'Tuần ' . $bucketStart->format('d/m');
                $next        = $cursor->copy()->next('Monday');
            } else {
                $bucketStart = $cursor->copy()->startOfMonth();
                $bucketEnd   = $cursor->copy()->endOfMonth();
                if ($bucketStart->lt($from)) $bucketStart = $from->copy();
                if ($bucketEnd->gt($to))     $bucketEnd   = $to->copy();
                $label       = $cursor->format('m/Y');
                $next        = $cursor->copy()->addMonthNoOverflow()->startOfMonth();
            }

            $revenue = Order::where('status', 'delivered')
                ->whereBetween('created_at', [$bucketStart, $bucketEnd])
                ->sum('total');

            $labels[] = $label;
            $data[]   = (float) $revenue;

            $cursor = $next;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Doanh thu (₫)',
                    'data'            => $data,
                    'borderColor'     => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension'         => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
