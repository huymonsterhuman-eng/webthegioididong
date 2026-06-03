<?php

namespace App\Filament\Pages;

/**
 * Trang Dashboard chính của Admin.
 *
 * Bố cục:
 *  - Header: `DashboardFilterForm` widget (Từ ngày / Đến ngày + nút Áp dụng).
 *  - Body: các stat widget + chart widget được auto-discover.
 *
 * Cơ chế filter: DashboardFilterForm dispatch event `dashboardFilterApplied`
 * qua Livewire, các widget có ý nghĩa theo thời gian lắng nghe và re-render.
 */
class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon  = 'heroicon-o-home';
    protected static ?string $navigationLabel = '📊 Dashboard';
    protected static ?string $title           = 'Dashboard';

    // DashboardFilterForm widget được auto-discover từ folder Widgets,
    // có sort = -1 nên tự động xuất hiện đầu tiên, full width.
}
