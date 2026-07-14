<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ListensToDashboardFilter;
use App\Models\ChatbotMessage;
use App\Models\ChatbotSession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * KPI Chatbot: số lượt trò chuyện, tin nhắn, tỉ lệ khách đã login, thời gian phản hồi trung bình.
 * Filter theo khoảng ngày từ DashboardFilterForm; nếu chưa chọn → mặc định 30 ngày.
 */
class ChatbotStatsWidget extends BaseWidget
{
    use ListensToDashboardFilter;

    public static function canView(): bool
    {
        return auth()->user()?->can('view_reports') || auth()->user()?->hasRole('super-admin');
    }

    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        [$from, $to] = $this->dateRange();
        $rangeLabel = $from->format('d/m') . ' → ' . $to->format('d/m');

        $sessionsCount = ChatbotSession::whereBetween('created_at', [$from, $to])->count();
        $messagesCount = ChatbotMessage::whereBetween('created_at', [$from, $to])->count();

        $loggedInSessions = ChatbotSession::whereBetween('created_at', [$from, $to])
            ->whereNotNull('user_id')
            ->count();

        $loggedInRate = $sessionsCount > 0
            ? round($loggedInSessions / $sessionsCount * 100, 1)
            : 0;

        $avgResponseMs = (int) ChatbotMessage::whereBetween('created_at', [$from, $to])
            ->where('role', 'model')
            ->whereNotNull('response_time_ms')
            ->avg('response_time_ms');

        return [
            Stat::make('Lượt trò chuyện', number_format($sessionsCount))
                ->description("Trong khoảng {$rangeLabel}")
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary'),

            Stat::make('Tin nhắn', number_format($messagesCount))
                ->description("Trong khoảng {$rangeLabel}")
                ->descriptionIcon('heroicon-m-envelope')
                ->color('info'),

            Stat::make('Khách đã đăng nhập', $loggedInRate . '%')
                ->description("{$loggedInSessions}/{$sessionsCount} lượt")
                ->descriptionIcon('heroicon-m-user-circle')
                ->color($loggedInRate >= 30 ? 'success' : 'warning'),

            Stat::make('Thời gian phản hồi TB', number_format($avgResponseMs) . ' ms')
                ->description('AI Gemini response time')
                ->descriptionIcon('heroicon-m-clock')
                ->color($avgResponseMs < 3000 ? 'success' : ($avgResponseMs < 6000 ? 'warning' : 'danger')),
        ];
    }
}
