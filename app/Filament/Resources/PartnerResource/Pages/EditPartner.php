<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Resources\PartnerResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, \App\Models\Partner $record) {
                    // Ngăn xóa supplier khi có phiếu nhập (restrictOnDelete đã bảo vệ ở DB level,
                    // nhưng thêm thông báo rõ ràng ở đây tốt hơn)
                    if ($record->type === 'supplier' && $record->goodsReceipts()->exists()) {
                        Notification::make()->danger()
                            ->title('Không thể xóa nhà cung cấp')
                            ->body("Đối tác \"{$record->name}\" đang có " . $record->goodsReceipts()->count() . " phiếu nhập kho. Hãy đặt trạng thái Ngừng hoạt động thay vì xóa.")
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        // Cảnh báo nếu sửa tên partner đang có giao dịch
        // (snapshot đã được lưu, lịch sử cũ an toàn — đây chỉ là thông tin)
        if ($this->data['name'] !== $record->getOriginal('name')) {
            $receiptCount = $record->goodsReceipts()->count();
            $orderCount   = \App\Models\Order::where('partner_id', $record->id)->count();

            if ($receiptCount > 0 || $orderCount > 0) {
                Notification::make()->warning()
                    ->title('Tên đối tác đã thay đổi')
                    ->body("Lịch sử cũ ({$receiptCount} phiếu nhập, {$orderCount} đơn hàng) vẫn giữ nguyên tên cũ nhờ snapshot.")
                    ->send();
            }
        }
    }
}
