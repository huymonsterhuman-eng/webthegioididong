<?php

namespace App\Filament\Resources\SystemActivityResource\Pages;

use App\Filament\Resources\SystemActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSystemActivities extends ManageRecords
{
    protected static string $resource = SystemActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create actions
        ];
    }
}
