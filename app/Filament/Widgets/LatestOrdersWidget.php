<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Storage;

class LatestOrdersWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->with(['user', 'orderDetails'])
                    ->latest()
                    ->limit(8)
            )
            ->heading('Latest Orders')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->formatStateUsing(fn($state) => '#' . $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Customer')
                    ->default('Guest')
                    ->limit(12),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date('d/m/Y'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money('VND', true),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
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
