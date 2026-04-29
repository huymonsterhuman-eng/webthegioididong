<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record, Actions\DeleteAction $action) {
                    if ($record->products()->count() > 0) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Không thể xóa danh mục này vì vẫn còn ' . $record->products()->count() . ' sản phẩm bên trong. Vui lòng chuyển sản phẩm sang danh mục khác trước.')
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }
}
