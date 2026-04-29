<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Widgets\UserStatsWidget;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserStatsWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tất cả'),
            'active' => Tab::make('Đang hoạt động')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active'))
                ->badge(\App\Models\User::where('status', 'active')->count())
                ->badgeColor('success'),
            'unverified' => Tab::make('Chưa xác minh')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'unverified'))
                ->badge(\App\Models\User::where('status', 'unverified')->count())
                ->badgeColor('warning'),
            'banned' => Tab::make('Đã chặn')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'banned'))
                ->badge(\App\Models\User::where('status', 'banned')->count())
                ->badgeColor('danger'),
        ];
    }
}
