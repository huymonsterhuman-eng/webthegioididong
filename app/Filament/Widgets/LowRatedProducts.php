<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LowRatedProducts extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view_dashboard') || auth()->user()->hasRole('super-admin');
    }

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->with(['category'])
                    ->withAvg(['reviews' => fn($query) => $query->where('is_hidden', false)], 'rating')
                    ->withCount(['reviews' => fn($query) => $query->where('is_hidden', false)])
                    ->having('reviews_avg_rating', '<=', 3)
                    ->having('reviews_count', '>', 0)
            )
            ->heading('Products Needing Attention')
            ->description('Average rating ≤ 3 stars')
            ->defaultSort('reviews_avg_rating', 'asc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Thumbnail')
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('reviews_avg_rating')
                    ->label('Avg Rating')
                    ->numeric(
                        decimalPlaces: 1,
                    )
                    ->sortable()
                    ->color('danger')
                    ->icon('heroicon-s-star'),
                Tables\Columns\TextColumn::make('reviews_count')
                    ->label('Total Reviews')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_product_reviews')
                    ->label('View Reviews')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Product $record): string => route('filament.admin.resources.reviews.index', ['tableFilters[product_id][value]' => $record->id]))
            ]);
    }
}
