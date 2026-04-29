<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ActivityLogResource extends Resource
{
    use \App\Filament\Traits\HasResourcePermission;
    protected static string $requiredPermission = 'view_activity_logs';
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationGroup = '🏭 Kho & Vận chuyển (Logistics)';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    protected static ?string $navigationLabel = 'Nhật ký kho';
    protected static ?string $modelLabel = 'Nhật ký kho';
    protected static ?string $pluralModelLabel = 'Nhật ký kho';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('action_type', 'inventory');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Read-only
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Người thực hiện')
                    ->sortable()
                    ->searchable(),
                // Action type column removed as it's scoped to inventory

                Tables\Columns\TextColumn::make('action')
                    ->label('Hành động')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('description')
                    ->label('Mô tả chi tiết')
                    ->searchable()
                    ->wrap()
                    ->limit(100),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Đối tượng')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$state) return 'N/A';
                        $mapping = [
                            'App\Models\Order' => 'Đơn hàng',
                            'App\Models\GoodsIssue' => 'Phiếu xuất kho',
                            'App\Models\GoodsReceipt' => 'Phiếu nhập kho',
                            'App\Models\GoodsReceiptDetail' => 'Lô hàng nhập',
                            'App\Models\Product' => 'Sản phẩm',
                            'App\Models\User' => 'Người dùng',
                        ];
                        $modelName = $mapping[$state] ?? class_basename($state);
                        
                        // For even more detail if it's a batch
                        if ($state === 'App\Models\GoodsReceiptDetail' && isset($record->properties['parent_receipt_id'])) {
                            return "{$modelName} #{$record->subject_id} (P.Nhập #{$record->properties['parent_receipt_id']})";
                        }
                        
                        return "{$modelName} #{$record->subject_id}";
                    })
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Action type filter removed as it's scoped to inventory
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')->label('Từ ngày'),
                        \Filament\Forms\Components\DatePicker::make('created_until')->label('Đến ngày'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Xem chi tiết'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Thông tin hoạt động')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')->label('Thời gian')->dateTime('d/m/Y H:i:s'),
                        Infolists\Components\TextEntry::make('user.username')->label('Người thực hiện'),
                        // Action type removed
                        Infolists\Components\TextEntry::make('action')->label('Mã hành động'),
                        Infolists\Components\TextEntry::make('description')->label('Mô tả chi tiết')->columnSpanFull(),
                    ])->columns(4),
                
                Infolists\Components\Section::make('Dữ liệu đính kèm (Properties)')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties')
                            ->label('Thông số chung')
                            ->getStateUsing(function ($record) {
                                $props = $record->properties ?? [];
                                unset($props['detailed_batches'], $props['detailed_items']); // Hide complex arrays from key-value view

                                $mapping = [
                                    'old_quantity' => 'Số lượng cũ',
                                    'new_quantity' => 'Số lượng mới',
                                    'difference' => 'Chênh lệch',
                                    'product_id' => 'Mã sản phẩm',
                                    'product_name' => 'Tên sản phẩm',
                                    'quantity_deducted' => 'Số lượng đã trừ',
                                    'quantity' => 'Số lượng',
                                    'old_remaining' => 'Tồn cũ trong lô',
                                    'new_remaining' => 'Tồn mới trong lô',
                                    'from_receipt_detail_id' => 'ID dòng lô hàng',
                                    'from_receipt' => 'Dòng lô hàng #',
                                    'parent_receipt_id' => 'Mã Phiếu Nhập gốc',
                                    'import_price' => 'Giá nhập của lô',
                                    'remaining_in_batch' => 'Tồn kho lô sau trừ',
                                    'remaining_in_receipt' => 'Tồn kho lô sau trừ',
                                    'issue_type' => 'Loại phiếu xuất',
                                    'receipt_type' => 'Loại phiếu nhập',
                                    'total_amount' => 'Tổng tiền nhập',
                                    'supplier_id' => 'Mã nhà cung cấp',
                                    'status' => 'Trạng thái',
                                    'module' => 'Mô-đun',
                                    'total_cogs' => 'Tổng vốn (COGS)',
                                    'item_count' => 'Số loại sản phẩm',
                                    'order_id' => 'Thuộc Đơn hàng #',
                                ];
                                $translated = [];
                                foreach ($props as $key => $value) {
                                    $newKey = $mapping[$key] ?? $key;
                                    $translated[$newKey] = $value;
                                }
                                return $translated;
                            })
                            ->columnSpanFull(),
                            
                        Infolists\Components\RepeatableEntry::make('properties.detailed_batches')
                            ->label('Chi tiết sản phẩm đã xuất (từ các lô hàng)')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_name')->label('Sản phẩm'),
                                Infolists\Components\TextEntry::make('quantity_taken')->label('Số lượng xuất'),
                                Infolists\Components\TextEntry::make('receipt_detail_id')->label('Từ Lô hàng #')->formatStateUsing(fn($state) => "Lô #{$state}"),
                                Infolists\Components\TextEntry::make('parent_receipt_id')->label('Của Phiếu Nhập #')->formatStateUsing(fn($state) => "PN #{$state}"),
                                Infolists\Components\TextEntry::make('import_price')->label('Giá nhập lô')->money('VND'),
                            ])
                            ->columns(5)
                            ->columnSpanFull()
                            ->visible(fn ($record) => isset($record->properties['detailed_batches']) && is_array($record->properties['detailed_batches'])),

                        // Detailed Items for Receipts
                        Infolists\Components\RepeatableEntry::make('properties.detailed_items')
                            ->label('Danh sách sản phẩm đã nhập')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_id')->label('ID sản phẩm'),
                                Infolists\Components\TextEntry::make('product_name')->label('Tên sản phẩm'),
                                Infolists\Components\TextEntry::make('quantity')->label('Số lượng nhập'),
                                Infolists\Components\TextEntry::make('import_price')->label('Giá nhập đơn vị')->money('VND'),
                                Infolists\Components\TextEntry::make('receipt_detail_id')->label('Vào Lô hàng #')->formatStateUsing(fn($state) => "Lô #{$state}"),
                            ])
                            ->columns(5)
                            ->columnSpanFull()
                            ->visible(fn ($record) => isset($record->properties['detailed_items']) && is_array($record->properties['detailed_items'])),
                    ])->visible(fn ($record) => !empty($record->properties)),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageActivityLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
