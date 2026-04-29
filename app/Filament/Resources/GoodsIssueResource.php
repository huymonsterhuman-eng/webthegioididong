<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\GoodsIssueResource\Pages;
use App\Models\GoodsIssue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GoodsIssueResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'manage_goods_issue';
    protected static ?string $model = GoodsIssue::class;

    protected static ?string $navigationGroup = '🏭 Kho & Vận chuyển (Logistics)';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-document-minus';
    protected static ?string $navigationLabel = 'Phiếu xuất kho';
    protected static ?string $modelLabel = 'Phiếu xuất kho';
    protected static ?string $pluralModelLabel = 'Phiếu xuất kho';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Thông tin chung')
                ->schema([
                    Forms\Components\Textarea::make('note')
                        ->label('Ghi chú (Lý do xuất)')
                        ->required()
                        ->maxLength(500)
                        ->columnSpanFull(),
                ]),
            
            Forms\Components\Section::make('Sản phẩm xuất kho')
                ->schema([
                    Forms\Components\Repeater::make('details')
                        ->label('Danh sách sản phẩm')
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Sản phẩm')
                                ->live() // Added to trigger reactivity
                                ->searchable()
                                ->getSearchResultsUsing(fn (string $search): array => \App\Models\Product::where('name', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                                ->getOptionLabelUsing(fn ($value): ?string => \App\Models\Product::find($value)?->name)
                                ->required()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Số lượng xuất')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->suffix(fn ($get) => $get('product_id') ? ' / Tồn: ' . (\App\Models\Product::find($get('product_id'))?->stock ?? 0) : '')
                                ->live()
                                ->rules([
                                    fn ($get) => function (string $attribute, $value, $fail) use ($get) {
                                        $productId = $get('product_id');
                                        if (!$productId) return;
                                        $product = \App\Models\Product::find($productId);
                                        if (!$product) return;
                                        if ((int)$value > (int)$product->stock) {
                                            $fail("Số lượng xuất ({$value}) vượt quá tồn kho hiện tại (" . (int)$product->stock . ").");
                                        }
                                    },
                                ]),
                        ])
                        ->columns(2)
                        ->addActionLabel('Thêm sản phẩm')
                        ->required()
                        ->minItems(1),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Mã Phiếu Xuất')
                    ->formatStateUsing(fn($state) => '#PX-' . str_pad($state, 4, '0', STR_PAD_LEFT))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.order_code')
                    ->label('Mã Đơn Hàng')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Loại phiếu')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'auto' => 'info',
                        'manual' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'auto' => 'Tự động (Từ Đơn hàng)',
                        'manual' => 'Thủ công',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('author.username')
                    ->label('Người thực hiện')
                    ->description(fn (GoodsIssue $record): ?string => $record->note)
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cogs')
                    ->label('Tổng giá trị xuất (COGS)')
                    ->numeric(0, ',', '.')
                    ->suffix(' ₫')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày xuất')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Chi tiết xuất kho'),
                Tables\Actions\Action::make('fix_missing_details')
                    ->label('Sửa lỗi chi tiết')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('warning')
                    ->visible(fn ($record) => $record->type === 'auto' && $record->status === 'completed' && $record->details()->count() === 0)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $totalCogs = 0;
                        $count = 0;
                        
                        if (!$record->order) return;

                        foreach ($record->order->orderDetails as $orderDetail) {
                            $neededQuantity = $orderDetail->quantity;
                            
                            $receipts = \App\Models\GoodsReceiptDetail::where('product_id', $orderDetail->product_id)
                                ->where('remaining_quantity', '>', 0)
                                ->orderBy('created_at', 'asc')
                                ->get();
                            
                                /** @var \App\Models\GoodsReceiptDetail $receipt */
                                foreach ($receipts as $receipt) {
                                    if ($neededQuantity <= 0) break;
                                    $take = min($neededQuantity, $receipt->remaining_quantity);
                                    $neededQuantity -= $take;
                                    $receipt->decrement('remaining_quantity', $take);
                                    
                                    $totalPrice = $take * $receipt->import_price;
                                    $totalCogs += $totalPrice;

                                    \App\Models\GoodsIssueDetail::create([
                                        'goods_issue_id' => $record->id,
                                        'goods_receipt_detail_id' => $receipt->id,
                                        'product_id' => $orderDetail->product_id,
                                        'quantity' => $take,
                                        'import_price' => $receipt->import_price,
                                        'total_price' => $totalPrice,
                                    ]);
                                    $count++;
                                }
                        }
                        
                        $record->update(['total_cogs' => $totalCogs]);
                        
                        if ($count > 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Đã sửa lỗi và bổ sung ' . $count . ' chi tiết xuất kho.')
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Không tìm thấy lô hàng còn tồn để bổ sung.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Thông tin phiếu xuất')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')->label('Mã Phiếu Xuất')
                            ->formatStateUsing(fn($state) => '#PX-' . str_pad($state, 4, '0', STR_PAD_LEFT)),
                        Infolists\Components\TextEntry::make('type')->label('Loại phiếu')
                            ->badge()
                            ->color(fn ($state): string => match ($state) {
                                'auto' => 'info',
                                'manual' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'auto' => 'Tự động',
                                'manual' => 'Thủ công',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('order.order_code')->label('Mã Đơn Hàng')->visible(fn($record) => $record->type === 'auto'),
                        Infolists\Components\TextEntry::make('author.full_name')->label('Người thực hiện')->visible(fn($record) => $record->type === 'manual'),
                        Infolists\Components\TextEntry::make('note')->label('Ghi chú')->visible(fn($record) => $record->type === 'manual')->columnSpanFull(),

                        Infolists\Components\TextEntry::make('status')->label('Trạng thái')
                            ->badge()
                            ->color(fn ($state): string => match ($state) {
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('created_at')->label('Ngày xuất')
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(4),
                
                Infolists\Components\View::make('filament.resources.goods-issue-resource.pages.view-goods-issue-details')
                    ->columnSpanFull()
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
            'index' => Pages\ListGoodsIssues::route('/'),
            'create' => Pages\CreateGoodsIssue::route('/create'),
            'view' => Pages\ViewGoodsIssue::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return true; 
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
