<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class DeadStockWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Top 5 Sản Phẩm Tồn Kho Lâu Nhất (Dead Stock)';

    public static function canView(): bool
    {
        return auth()->user()->can('view_reports') || auth()->user()->hasRole('super-admin');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('stock', '>', 0)
                    ->whereDoesntHave('orderDetails', function (Builder $query) {
                        $query->whereHas('order', function (Builder $o) {
                            $o->where('created_at', '>=', now()->subDays(30));
                        });
                    })
                    ->orderBy('stock', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Ảnh')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Sản phẩm')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Số lượng tồn')
                    ->badge()
                    ->color('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá bán')
                    ->money('VND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo mã')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->paginated(false)
            ->description('Sản phẩm có lượng tồn kho cao nhưng KHÔNG phát sinh đơn hàng trong 30 ngày qua.');
    }
}
