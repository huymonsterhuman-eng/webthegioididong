<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

/**
 * Trang Edit sản phẩm trong Filament admin.
 * Chặn việc chuyển sản phẩm sang "Ngừng kinh doanh" nếu còn đơn hàng chưa xử lý xong.
 */
class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Trước khi lưu: nếu admin đang chuyển is_active từ true → false,
     * kiểm tra xem sản phẩm có nằm trong đơn hàng pending/confirmed/shipping
     * nào không. Nếu có → chặn và hiển thị thông báo.
     */
    protected function beforeSave(): void
    {
        $data   = $this->data;
        $record = $this->record;

        // Chặn chuyển sang Ngừng kinh doanh nếu có đơn hàng đang xử lý
        $changingToInactive = isset($data['is_active']) && ! $data['is_active'] && $record->is_active;

        if ($changingToInactive) {
            $hasPendingOrders = \App\Models\OrderDetail::where('product_id', $record->id)
                ->whereHas('order', fn ($q) => $q->whereIn('status', ['pending', 'confirmed', 'shipping']))
                ->exists();

            if ($hasPendingOrders) {
                Notification::make()
                    ->danger()
                    ->title('Không thể ngừng kinh doanh')
                    ->body("Sản phẩm \"{$record->name}\" đang có trong đơn hàng chờ xử lý. Hãy hoàn tất hoặc hủy các đơn hàng đó trước.")
                    ->send();

                $this->halt();
            }
        }
    }
}
