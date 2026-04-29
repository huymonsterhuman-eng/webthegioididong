<?php

namespace App\Filament\Resources\GoodsReceiptDetailResource\Pages;

use App\Filament\Resources\GoodsReceiptDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGoodsReceiptDetail extends EditRecord
{
    protected static string $resource = GoodsReceiptDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
