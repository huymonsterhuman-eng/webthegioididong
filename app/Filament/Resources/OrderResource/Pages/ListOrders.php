<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Order;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\OrderResource\Widgets\OrderStatsWidget::class,
        ];
    }
    
    // Move widget to separate folder or use full path if created in generic widgets
    // I created it in App\Filament\Widgets, let's use that.
    public function getHeaderWidgetsColumns(): int | array
    {
        return 4;
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tất cả'),
            'pending' => Tab::make('Chờ xử lý')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(Order::query()->where('status', 'pending')->count())
                ->badgeColor('warning'),
            'confirmed' => Tab::make('Đã xác nhận')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'confirmed'))
                ->badge(Order::query()->where('status', 'confirmed')->count())
                ->badgeColor('info'),
            'shipping' => Tab::make('Đang giao')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'shipping'))
                ->badge(Order::query()->where('status', 'shipping')->count())
                ->badgeColor('primary'),
            'delivered' => Tab::make('Đã giao')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'delivered'))
                ->badge(Order::query()->where('status', 'delivered')->count())
                ->badgeColor('success'),
            'cancelled' => Tab::make('Đã hủy')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled'))
                ->badge(Order::query()->where('status', 'cancelled')->count())
                ->badgeColor('danger'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
