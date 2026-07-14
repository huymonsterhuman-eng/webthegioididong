<?php

namespace App\Filament\Widgets;

use App\Models\ChatbotMessage;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

/**
 * Top 10 câu hỏi hay gặp của khách trong 30 ngày gần nhất.
 * Group theo prefix 100 ký tự đầu (lowercase) để gộp các câu tương tự.
 */
class ChatbotTopQuestionsWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->can('view_reports') || auth()->user()?->hasRole('super-admin');
    }

    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 1;

    protected static ?string $heading = 'Top câu hỏi hay gặp (30 ngày)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ChatbotMessage::query()
                    ->where('role', 'user')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->selectRaw('MIN(id) as id, LOWER(SUBSTRING(content, 1, 100)) as question, COUNT(*) as ask_count, MAX(created_at) as last_asked')
                    ->groupBy('question')
                    ->orderByDesc('ask_count')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Câu hỏi')
                    ->wrap(),
                Tables\Columns\TextColumn::make('ask_count')
                    ->label('Số lần hỏi')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_asked')
                    ->label('Lần cuối')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
