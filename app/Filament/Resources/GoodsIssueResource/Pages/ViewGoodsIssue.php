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

    /** Action Duyệt/Bàn giao ĐVVC và Từ chối — cho cả manual và auto pending */
    protected function getHeaderActions(): array
    {
        return [
            // ✅ Duyệt (manual) hoặc Bàn giao ĐVVC (auto)
            Actions\Action::make('approve_issue')
                ->label(fn (GoodsIssue $record) => $record->type === 'auto' ? '🚚 Bàn giao ĐVVC' : '✅ Duyệt phiếu xuất')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(fn (GoodsIssue $record) => $record->type === 'auto'
                    ? 'Xác nhận bàn giao hàng cho đơn vị vận chuyển?'
                    : 'Duyệt phiếu xuất kho?')
                ->modalDescription(fn (GoodsIssue $record) => $record->type === 'auto'
                    ? 'Tồn kho sẽ bị trừ theo FIFO và đơn hàng chuyển sang "Đang giao hàng". Không thể hoàn tác.'
                    : 'Hành động này sẽ trừ tồn kho theo FIFO và không thể hoàn tác.')
                ->visible(fn (GoodsIssue $record) => $record->isPending())
                ->action(function (GoodsIssue $record) {
                    $inventoryService = new InventoryService();

                    try {
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record, $inventoryService) {
                            if ($record->type === 'auto' && $record->order_id) {
                                // Auto phiếu từ đơn hàng: xóa stubs, chạy FIFO theo orderDetails
                                $record->details()->delete();

                                $allBatches = [];
                                foreach ($record->order->orderDetails as $orderDetail) {
                                    $result = $inventoryService->reduceStock(
                                        $orderDetail->product_id,
                                        $orderDetail->quantity,
                                        $record
                                    );
                                    $allBatches = array_merge($allBatches, $result['batches']);
                                }

                                $totalCogs = $record->details()->sum('total_price');
                                $record->update(['status' => 'completed', 'total_cogs' => $totalCogs]);
                                $record->order->update(['status' => 'shipping']);

                                \App\Services\ActivityLogService::log(
                                    'order_shipped',
                                    "Phiếu xuất #{$record->id} hoàn thành. Đơn #{$record->order_id} → Đang giao hàng.",
                                    'inventory', $record,
                                    ['order_id' => $record->order_id, 'batches' => $allBatches]
                                );
                            } else {
                                // Manual phiếu
                                foreach ($record->details()->whereNull('goods_receipt_detail_id')->get() as $stub) {
                                    $product = \App\Models\Product::find($stub->product_id);
                                    if ($product && $product->available_stock < $stub->quantity) {
                                        throw new \Exception("Sản phẩm \"{$product->name}\" chỉ còn {$product->available_stock} cái, cần {$stub->quantity} cái.");
                                    }
                                }
                                foreach ($record->details()->whereNull('goods_receipt_detail_id')->get() as $stub) {
                                    $inventoryService->reduceStock($stub->product_id, $stub->quantity, $record);
                                }
                                $record->details()->whereNull('goods_receipt_detail_id')->delete();
                                $totalCogs = $record->details()->sum('total_price');
                                $record->update(['status' => 'completed', 'total_cogs' => $totalCogs]);

                                \App\Services\ActivityLogService::log(
                                    'approve_manual_issue',
                                    "Đã duyệt phiếu xuất thủ công #{$record->id}.",
                                    'inventory', $record, []
                                );
                            }
                        });

                        $msg = $record->type === 'auto'
                            ? 'Đã bàn giao ĐVVC — đơn hàng chuyển sang Đang giao hàng'
                            : 'Đã duyệt phiếu xuất — tồn kho đã được trừ';
                        Notification::make()->success()->title($msg)->send();

                        $this->refreshFormData(['status', 'total_cogs']);
                    } catch (\Exception $e) {
                        Notification::make()->danger()
                            ->title('Lỗi: ' . $e->getMessage())
                            ->send();
                    }
                }),

            // ❌ Từ chối / Hủy phiếu xuất pending
            Actions\Action::make('reject_issue')
                ->label('❌ Từ chối')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Từ chối / Hủy phiếu xuất?')
                ->modalDescription('Phiếu sẽ bị huỷ. Tồn kho không thay đổi vì FIFO chưa chạy.')
                ->visible(fn (GoodsIssue $record) => $record->isPending())
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
                                'steps' => [
                                    ['key' => 'pending',   'label' => $record->type === 'auto' ? 'Chờ bàn giao ĐVVC' : 'Chờ duyệt'],
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
