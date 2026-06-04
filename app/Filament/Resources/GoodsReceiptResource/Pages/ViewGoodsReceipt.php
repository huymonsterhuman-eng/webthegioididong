<?php

namespace App\Filament\Resources\GoodsReceiptResource\Pages;

use App\Filament\Resources\GoodsReceiptResource;
use App\Models\GoodsReceipt;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

/**
 * Trang xem chi tiết phiếu nhập kho. Hiển thị status stepper + 2 action:
 *   - "Xác nhận nhập kho": pending → completed (cộng stock).
 *   - "Huỷ phiếu": pending → cancelled (không động stock).
 */
class ViewGoodsReceipt extends ViewRecord
{
    protected static string $resource = GoodsReceiptResource::class;

    /** Nút Xác nhận / Huỷ phiếu — chỉ hiện khi status = pending */
    protected function getHeaderActions(): array
    {
        return [
            // ✅ Xác nhận nhập kho (pending → completed)
            Actions\Action::make('confirm_receipt')
                ->label('✅ Xác nhận nhập kho')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Xác nhận nhập kho?')
                ->modalDescription('Hành động này sẽ cộng số lượng vào tồn kho và không thể hoàn tác.')
                ->visible(fn (GoodsReceipt $record) => $record->isPending())
                ->action(function (GoodsReceipt $record) {
                    foreach ($record->details as $detail) {
                        if ($detail->product) {
                            $detail->product->increment('stock', $detail->quantity);
                        }
                        $detail->update(['remaining_quantity' => $detail->quantity]);
                    }
                    $record->update(['status' => 'completed']);

                    \App\Services\ActivityLogService::log(
                        'confirm_goods_receipt',
                        "Đã xác nhận nhập kho phiếu #{$record->id}. Tổng: {$record->total_amount}đ",
                        'inventory',
                        $record,
                        ['total_amount' => $record->total_amount]
                    );

                    Notification::make()->success()
                        ->title('Nhập kho thành công')
                        ->body('Tồn kho đã được cập nhật.')
                        ->send();
                }),

            // ❌ Huỷ phiếu (pending → cancelled)
            Actions\Action::make('cancel_receipt')
                ->label('❌ Huỷ phiếu')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Huỷ phiếu nhập?')
                ->modalDescription('Phiếu sẽ bị huỷ. Vì chưa nhập kho nên tồn kho không thay đổi.')
                ->visible(fn (GoodsReceipt $record) => $record->isPending())
                ->action(function (GoodsReceipt $record) {
                    $record->update(['status' => 'cancelled']);
                    Notification::make()->warning()
                        ->title('Đã huỷ phiếu nhập')
                        ->send();
                }),

            Actions\EditAction::make()
                ->visible(fn (GoodsReceipt $record) => $record->isPending()),
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
                                    ['key' => 'pending',   'label' => 'Chờ xác nhận'],
                                    ['key' => 'completed', 'label' => 'Hoàn thành'],
                                ],
                                'current'   => $record->status,
                                'cancelled' => $record->status === 'cancelled',
                            ]),
                    ])
                    ->collapsible(false),

                Infolists\Components\Section::make('Thông tin phiếu nhập')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Mã phiếu')
                            ->formatStateUsing(fn ($state) => 'PR-' . str_pad($state, 4, '0', STR_PAD_LEFT)),
                        Infolists\Components\TextEntry::make('supplier_name')
                            ->label('Nhà cung cấp')
                            ->default(fn ($record) => $record->supplier?->name),
                        Infolists\Components\TextEntry::make('user.username')
                            ->label('Người tạo'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Tổng giá trị')
                            ->formatStateUsing(fn(\) => number_format((float)(\ ?? 0), 0, ',', '.') . ' ₫'),
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
                                'pending'   => 'Chờ xác nhận',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã huỷ',
                                default     => $state,
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime('d/m/Y H:i'),
                        Infolists\Components\TextEntry::make('note')
                            ->label('Ghi chú')
                            ->columnSpanFull(),
                    ])->columns(3),

                Infolists\Components\Section::make('Sản phẩm nhập')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('details')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Sản phẩm'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Số lượng'),
                                Infolists\Components\TextEntry::make('import_price')
                                    ->label('Giá nhập')
                                    ->formatStateUsing(fn(\) => number_format((float)(\ ?? 0), 0, ',', '.') . ' ₫'),
                            ])
                            ->columns(3)
                            ->label(''),
                    ]),
            ]);
    }
}
