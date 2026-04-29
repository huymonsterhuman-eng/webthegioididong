<?php

namespace App\Filament\Resources\GoodsIssueResource\Pages;

use App\Filament\Resources\GoodsIssueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGoodsIssues extends ListRecords
{
    protected static string $resource = GoodsIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Resources\Components\Tab::make('Tất cả'),
            'auto' => \Filament\Resources\Components\Tab::make('Từ Đơn Hàng')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('type', 'auto')),
            'manual' => \Filament\Resources\Components\Tab::make('Thủ Công')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('type', 'manual')),
        ];
    }
}
