<?php

namespace App\Filament\Resources\GoodsIssueResource\Pages;

use App\Filament\Resources\GoodsIssueResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\InventoryService;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Trang tạo phiếu xuất kho thủ công.
 * Khi tạo: status = pending, không chạy FIFO ngay.
 * Tạo stub details (goods_receipt_detail_id = null) để chờ admin duyệt.
 */
class CreateGoodsIssue extends CreateRecord
{
    protected static string $resource = GoodsIssueResource::class;

    /** Gán mặc định: type=manual, status=pending, author = current admin */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type']      = 'manual';
        $data['author_id'] = auth()->id();
        $data['status']    = 'pending';   // Chờ duyệt — stock chưa bị trừ
        $data['total_cogs'] = 0;

        return $data;
    }

    /**
     * Tạo phiếu xuất + stub details trong 1 transaction.
     * FIFO chưa chạy — sẽ chạy khi admin duyệt phiếu ở trang View/List.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $details = $data['details'] ?? [];
        unset($data['details']);

        $record = null;

        DB::transaction(function () use (&$record, $data, $details) {
            $record = static::getModel()::create($data);

            // Tạo stub details — chưa gán batch FIFO, chờ admin duyệt mới trừ stock
            foreach ($details as $item) {
                \App\Models\GoodsIssueDetail::create([
                    'goods_issue_id'          => $record->id,
                    'goods_receipt_detail_id' => null,   // Sẽ gán khi duyệt
                    'product_id'              => $item['product_id'],
                    'quantity'                => $item['quantity'],
                    'import_price'            => 0,
                    'total_price'             => 0,
                ]);
            }

            \App\Services\ActivityLogService::log(
                'create_manual_issue',
                "Đã tạo phiếu xuất kho thủ công #{$record->id} với " . count($details) . " loại sản phẩm. Đang chờ duyệt.",
                'inventory',
                $record,
                ['item_count' => count($details)]
            );
        });

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
