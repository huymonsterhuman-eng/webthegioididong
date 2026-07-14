<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ListensToDashboardFilter;
use App\Models\ChatbotMessage;
use Filament\Widgets\ChartWidget;

/**
 * Biểu đồ số tin nhắn chatbot theo thời gian.
 * Smart bucket: ≤31d → ngày, ≤180d → tuần, >180d → tháng (giống RevenueChart).
 */
class ChatbotVolumeChart extends ChartWidget
{
    use ListensToDashboardFilter;

    public static function canView(): bool
    {
        return auth()->user()?->can('view_reports') || auth()->user()?->hasRole('super-admin');
    }

    protected static ?int $sort = 5;
    protected int|string|array $columnSpan = 1;

    public function getHeading(): string
    {
        [$from, $to] = $this->dateRange();
        return 'Lưu lượng chatbot ' . $from->format('d/m/Y') . ' → ' . $to->format('d/m/Y');
    }

    protected function getData(): array
    {
        [$from, $to] = $this->dateRange();
        $totalDays = (int) $from->diffInDays($to) + 1;

        if ($totalDays <= 31) {
            $bucket = 'day';
        } elseif ($totalDays <= 180) {
            $bucket = 'week';
        } else {
            $bucket = 'month';
        }

        $labels = [];
        $data = [];
        $cursor = $from->copy();

        while ($cursor->lte($to)) {
            if ($bucket === 'day') {
                $bucketStart = $cursor->copy()->startOfDay();
                $bucketEnd = $cursor->copy()->endOfDay();
                $label = $cursor->format('d/m');
                $next = $cursor->copy()->addDay();
            } elseif ($bucket === 'week') {
                $bucketStart = $cursor->copy()->startOfWeek();
                $bucketEnd = $cursor->copy()->endOfWeek();
                if ($bucketStart->lt($from)) $bucketStart = $from->copy();
                if ($bucketEnd->gt($to)) $bucketEnd = $to->copy();
                $label = 'Tuần ' . $bucketStart->format('d/m');
                $next = $cursor->copy()->next('Monday');
            } else {
                $bucketStart = $cursor->copy()->startOfMonth();
                $bucketEnd = $cursor->copy()->endOfMonth();
                if ($bucketStart->lt($from)) $bucketStart = $from->copy();
                if ($bucketEnd->gt($to)) $bucketEnd = $to->copy();
                $label = $cursor->format('m/Y');
                $next = $cursor->copy()->addMonthNoOverflow()->startOfMonth();
            }

            $count = ChatbotMessage::whereBetween('created_at', [$bucketStart, $bucketEnd])->count();

            $labels[] = $label;
            $data[] = $count;

            $cursor = $next;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Số tin nhắn',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.15)',
                    'tension' => 0.3,
                    'fill' => true,
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
