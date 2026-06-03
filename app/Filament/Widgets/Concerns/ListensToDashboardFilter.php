<?php

namespace App\Filament\Widgets\Concerns;

use Carbon\Carbon;
use Livewire\Attributes\On;

/**
 * Trait dùng chung cho widget Dashboard cần lắng nghe filter thời gian.
 *
 * Cách dùng:
 *   1. `use ListensToDashboardFilter;` trong widget.
 *   2. Trong query: gọi `[$from, $to] = $this->dateRange();` để lấy range.
 *
 * Default: 30 ngày gần nhất (today - 30 → today). Khi user bấm "Áp dụng" trong
 * DashboardFilterForm → event `dashboardFilterApplied` được dispatch → trait này
 * cập nhật $startDate / $endDate → widget tự re-render với query mới.
 */
trait ListensToDashboardFilter
{
    public ?string $startDate = null;
    public ?string $endDate   = null;

    /**
     * Listener cho event từ DashboardFilterForm.
     */
    #[On('dashboardFilterApplied')]
    public function applyDashboardDateFilter(?string $startDate, ?string $endDate): void
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
     * Trả về [Carbon $from, Carbon $to] đã chuẩn hoá biên ngày.
     * Default 30 ngày nếu chưa có filter.
     */
    protected function dateRange(): array
    {
        $from = $this->startDate
            ? Carbon::parse($this->startDate)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $to = $this->endDate
            ? Carbon::parse($this->endDate)->endOfDay()
            : now()->endOfDay();

        return [$from, $to];
    }

    /**
     * Số ngày trong range hiện tại (dùng cho label động "Trong X ngày").
     */
    protected function daysInRange(): int
    {
        [$from, $to] = $this->dateRange();
        return (int) $from->diffInDays($to) + 1;
    }
}
