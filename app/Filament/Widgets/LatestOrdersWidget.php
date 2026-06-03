<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\ListensToDashboardFilter;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/**
 * Widget bảng đơn hàng mới nhất.
 *
 * Hiển thị 8 đơn mới nhất trong khoảng thời gian filter từ Dashboard.
 */
class LatestOrdersWidget extends BaseWidget
{
    use ListensToDashboardFilter;

    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        [$from, $to] = $this->dateRange();

        return $table
            ->query(
                Order::query()
                    ->with(['user', 'orderDetails'])
                    ->whereBetween('created_at', [$from, $to])
                    ->latest()
                    ->limit(8)
            )
            ->heading('Đơn hàng mới nhất')
            ->description($from->format('d/m/Y') . ' → ' . $to->format('d/m/Y'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Mã đơn')
                    ->formatStateUsing(fn($state) => '#' . $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Khách hàng')
                    ->default('Guest')
                    ->limit(12),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày đặt')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Tổng tiền')
                    ->money('VND', true),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Trạng thái')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'confirmed',
                        'primary' => 'shipping',
                        'success' => 'delivered',
                        'danger'  => 'cancelled',
                    ]),
            ])
            ->paginated(false);
    }
}
