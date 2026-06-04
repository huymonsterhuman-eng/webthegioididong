<?php

namespace App\Filament\Resources\GoodsIssueResource\Pages;

use App\Filament\Resources\GoodsIssueResource;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueDetail;
use App\Services\InventoryService;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

/**
 * Trang xem chi tiết phiếu xuất kho. Hiển thị status stepper + 2 action
 * (chỉ cho phiếu thủ công manual ở status pending):
 *   - "Duyệt phiếu xuất": chạy FIFO trừ kho → status = completed.
 *   - "Từ chối": status = cancelled, stock không đổi.
 */
class ViewGoodsIssue extends ViewRecord
{
    protected static string $resource = GoodsIssueResource::class;

    /** Action Duyệt / Từ chối — chỉ hiện cho phiếu manual đang pending */
    protected function getHeaderActions(): array
    {
        return [
            // ✅ Duyệt phiếu xuất (pending → completed) — chỉ manual
            Actions\Action::make('approve_issue')
                ->label('✅ Duyệt phiếu xuất')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Duyệt phiếu xuất kho?')
                ->modalDescription('Hành động này sẽ trừ tồn kho theo FIFO và không thể hoàn tác.')
                ->visible(fn (GoodsIssue $record) => $record->type === 'manual' && $record->isPending())
                ->action(function (GoodsIssue $record) {
                    // Kiểm tra available_stock trước khi duyệt
                    foreach ($record->details()->whereNull('goods_receipt_detail_id')->get() as $stub) {
                        $product = \App\Models\Product::find($stub->product_id);
                        if ($product && $product->available_stock < $stub->quantity) {
                            Notification::make()->danger()
                                ->title('Không đủ stock khả dụng')
                                ->body("Sản phẩm \"{$product->name}\" chỉ còn {$product->available_stock} cái khả dụng, cần {$stub->quantity} cái.")
                                ->send();
                            return;
                        }
                    }

                    $inventoryService = new InventoryService();
                    $totalCogs = 0;
                    $allBatches = [];

                    try {
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $inventoryService, &$totalCogs, &$allBatches) {
                            foreach ($record->details as $stubDetail) {
                                $result = $inventoryService->reduceStock(
                                    $stubDetail->product_id,
                                    $stubDetail->quantity,
                                    $record
                                );
                                $allBatches = array_merge($allBatches, $result['batches']);
                            }
                            $record->details()->whereNull('goods_receipt_detail_id')->delete();
                            $totalCogs = $record->details()->sum('total_price');
                            $record->update(['status' => 'completed', 'total_cogs' => $totalCogs]);
                        });

                        \App\Services\ActivityLogService::log(
                            'approve_manual_issue',
                            "Đã duyệt phiếu xuất kho thủ công #{$record->id}. COGS: {$totalCogs}đ",
                            'inventory',
                            $record,
                            ['total_cogs' => $totalCogs, 'batches' => $allBatches]
                        );

                        Notification::make()->success()
                            ->title('Đã duyệt phiếu xuất')
                            ->body('Tồn kho đã được trừ theo FIFO.')
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()->danger()
                            ->title('Lỗi khi duyệt')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            // ❌ Từ chối phiếu xuất (pending → cancelled)
            Actions\Action::make('reject_issue')
                ->label('❌ Từ chối')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Từ chối phiếu xuất?')
                ->modalDescription('Phiếu sẽ bị huỷ. Tồn kho không thay đổi.')
                ->visible(fn (GoodsIssue $record) => $record->type === 'manual' && $record->isPending())
                ->action(function (GoodsIssue $record) {
                    $record->update(['status' => 'cancelled']);
                    Notification::make()->warning()
                        ->title('Đã từ chối phiếu xuất')
                        ->send();
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Thanh trạng thái stepper
                Infolists\Components\Section::make('Trạng thái phiếu')
                    ->schema([
                        Infolists\Components\View::make('components.status-stepper')
                            ->viewData(fn ($record) => [
                                'steps' => $record->type === 'manual'
                                    ? [
                                        ['key' => 'pending',   'label' => 'Chờ duyệt'],
                                        ['key' => 'completed', 'label' => 'Hoàn thành'],
                                      ]
                                    : [
                                        ['key' => 'completed', 'label' => 'Hoàn thành'],
                                      ],
                                'current'   => $record->status,
                                'cancelled' => $record->status === 'cancelled',
                            ]),
                    ])
                    ->collapsible(false),

                // Thông tin phiếu (dùng infolist của resource)
                Infolists\Components\Section::make('Thông tin phiếu xuất')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Mã phiếu')
                            ->formatStateUsing(fn ($state) => '#PX-' . str_pad($state, 4, '0', STR_PAD_LEFT)),
                        Infolists\Components\TextEntry::make('type')
                            ->label('Loại phiếu')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state === 'auto' ? 'Tự động (Từ Đơn hàng)' : 'Thủ công')
                            ->color(fn ($state) => $state === 'auto' ? 'info' : 'warning'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'pending'   => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default     => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'pending'   => 'Chờ duyệt',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã huỷ',
                                default     => $state,
                            }),
                        Infolists\Components\TextEntry::make('total_cogs')
                            ->label('Tổng giá trị xuất (COGS)')
                            ->formatStateUsing(fn($state) => number_format((float)($state ?? 0), 0, ',', '.') . ' ₫'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('author.username')
                            ->label('Người tạo')
                            ->visible(fn ($record) => $record->type === 'manual'),
                        Infolists\Components\TextEntry::make('order.id')
                            ->label('Đơn hàng')
                            ->formatStateUsing(fn ($state) => 'ORD-' . date('Ymd') . '-' . str_pad($state, 3, '0', STR_PAD_LEFT))
                            ->visible(fn ($record) => $record->type === 'auto' && $record->order_id),
                        Infolists\Components\TextEntry::make('note')
                            ->label('Ghi chú')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->type === 'manual'),
                    ])->columns(3),

                // Chi tiết sản phẩm
                Infolists\Components\View::make('filament.resources.goods-issue-resource.pages.view-goods-issue-details'),
            ]);
    }
}
