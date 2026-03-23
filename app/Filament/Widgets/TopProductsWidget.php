<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_dashboard') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->withCount([
                        'orderDetails as total_sold' => function (Builder $query) {
                            $query->select(DB::raw('COALESCE(SUM(quantity), 0)'));
                        }
                    ])
                    ->having('total_sold', '>', 0)
                    ->orderByDesc('total_sold')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->defaultImageUrl(url('storage/img/placeholder.jpg'))
                    ->square()
                    ->width(36)
                    ->height(36),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->limit(25),
                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Đơn vị đã bán')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Kho hiện tại')
                    ->badge()
                    ->color(fn ($state) => $state <= 0 ? 'danger' : ($state <= 5 ? 'warning' : 'gray')),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->money('VND', true),
            ])
            ->paginated(false);
    }
}
