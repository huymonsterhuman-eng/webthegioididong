<?php

namespace App\Filament\Resources\GoodsIssueResource\Pages;

use App\Filament\Resources\GoodsIssueResource;
use Filament\Resources\Pages\CreateRecord;
use App\Services\InventoryService;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class CreateGoodsIssue extends CreateRecord
{
    protected static string $resource = GoodsIssueResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'manual';
        $data['author_id'] = auth()->id();
        $data['status'] = 'completed';
        $data['total_cogs'] = 0; 

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $details = $data['details'] ?? [];
        unset($data['details']);

        $record = null;

        DB::transaction(function () use (&$record, $data, $details) {
            $record = static::getModel()::create($data);

            $inventoryService = new InventoryService();
            $totalCogs = 0;
            $allBatches = [];

            foreach ($details as $item) {
                try {
                    $result = $inventoryService->reduceStock(
                        $item['product_id'],
                        $item['quantity'],
                        $record
                    );
                    $totalCogs += $result['cogs'];
                    $allBatches = array_merge($allBatches, $result['batches']);
                } catch (Exception $e) {
                    Notification::make()
                        ->title('Lỗi xuất kho: ' . $e->getMessage())
                        ->danger()
                        ->send();
                        
                    // By throwing exception again, we rollback the entire transaction
                    throw $e; 
                }
            }

            $record->update(['total_cogs' => $totalCogs]);

            \App\Services\ActivityLogService::log(
                'create_manual_issue',
                "Đã tạo phiếu xuất kho thủ công #{$record->id} với " . count($details) . " loại sản phẩm.",
                'inventory',
                $record,
                [
                    'total_cogs' => $totalCogs, 
                    'item_count' => count($details),
                    'detailed_batches' => $allBatches
                ]
            );
        });

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
