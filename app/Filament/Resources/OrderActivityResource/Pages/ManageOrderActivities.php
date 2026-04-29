<?php

namespace App\Filament\Resources\OrderActivityResource\Pages;

use App\Filament\Resources\OrderActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOrderActivities extends ManageRecords
{
    protected static string $resource = OrderActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create actions
        ];
    }
}
