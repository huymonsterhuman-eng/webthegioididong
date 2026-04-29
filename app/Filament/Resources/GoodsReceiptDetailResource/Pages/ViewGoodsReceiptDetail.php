<?php

namespace App\Filament\Resources\GoodsReceiptDetailResource\Pages;

use App\Filament\Resources\GoodsReceiptDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGoodsReceiptDetail extends ViewRecord
{
    protected static string $resource = GoodsReceiptDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
