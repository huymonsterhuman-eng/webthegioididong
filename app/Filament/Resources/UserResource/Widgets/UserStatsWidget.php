<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Tổng Người dùng', User::count())
                ->description('Tất cả tài khoản trong hệ thống')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make('Đang hoạt động', User::where('status', 'active')->count())
                ->description('Tài khoản có thể đăng nhập')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Đã chặn', User::where('status', 'banned')->count())
                ->description('Tài khoản bị hạn chế truy cập')
                ->descriptionIcon('heroicon-m-no-symbol')
                ->color('danger'),
            Stat::make('Mới tuần này', User::where('created_at', '>=', now()->startOfWeek())->count())
                ->description('Số đăng ký mới từ đầu tuần')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary')
                ->chart([7, 10, 5, 2, 20, 30, 45]), // Dummy data for chart visual
        ];
    }
}
