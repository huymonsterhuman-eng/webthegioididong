<?php

namespace App\Filament\Resources\GoodsReceiptDetailResource\Pages;

use App\Filament\Resources\GoodsReceiptDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGoodsReceiptDetails extends ListRecords
{
    protected static string $resource = GoodsReceiptDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create actions
        ];
    }
}
