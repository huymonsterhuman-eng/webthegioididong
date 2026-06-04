<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\GoodsReceiptResource\Pages;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptDetail;
use App\Models\Partner;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GoodsReceiptResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'manage_goods_receipt';
    protected static ?string $model = GoodsReceipt::class;

    protected static ?string $navigationGroup = '🏭 Kho & Vận chuyển (Logistics)';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Phiếu nhập kho';
    protected static ?string $modelLabel = 'Phiếu nhập kho';
    protected static ?string $pluralModelLabel = 'Phiếu nhập kho';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Receipt Information')
                    ->disabled(fn ($record) => $record && $record->status !== 'pending')
                    ->schema([
                        Forms\Components\Select::make('supplier_id')
                            ->label('Supplier (Nhà cung cấp)')
                            ->options(
                                Partner::where('type', 'supplier')
                                    ->where('is_active', true)
                                    ->pluck('name', 'id')
                            )
                            ->required()
                            ->searchable(),
                        Forms\Components\Textarea::make('note')
                            ->label('Note / Ghi chú')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Products (Sản phẩm nhập)')
                    ->disabled(fn ($record) => $record && $record->status !== 'pending')
                    ->schema([
                        Forms\Components\Repeater::make('details')
                            ->label('')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(
                                        Product::active()
                                            ->orderBy('stock', 'asc')
                                            ->get()
                                            ->mapWithKeys(fn($p) => [
                                                $p->id => "[Tồn: {$p->stock}] {$p->name}"
                                            ])
                                    )
                                    ->required()
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if (!$state) return;
                                        $product = Product::find($state);
                                        if (!$product) return;

                                        // Fetch last import price from goods_receipt_details
                                        $lastDetail = GoodsReceiptDetail::where('product_id', $state)
                                            ->latest()
                                            ->first();

                                        $set('import_price', $lastDetail ? $lastDetail->import_price : 0);
                                        $set('retail_price_display', number_format($product->price, 0, ',', '.') . ' ₫');
                                    })
                                    ->columnSpan(3),

                                Forms\Components\TextInput::make('retail_price_display')
                                    ->label('Giá niêm yết')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->placeholder('Chọn sản phẩm...')
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('import_price')
                                    ->label('Giá nhập (last / mới)')
                                    ->numeric()
                                    ->prefix('₫')
                                    ->required()
                                    ->columnSpan(2)
                                    ->helperText('Tự động điền từ lần nhập gần nhất'),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Số lượng nhập')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->columnSpan(2),
                            ])
                            ->columns(9)
                            ->minItems(1)
                            ->addActionLabel('+ Thêm sản phẩm')
                            ->reorderable(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Receipt #')
                    ->formatStateUsing(fn($state) => 'PR-' . str_pad($state, 4, '0', STR_PAD_LEFT))
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier_name')
                    ->label('Nhà cung cấp')
                    ->searchable()
                    ->default(fn ($record) => $record->supplier?->name),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Created By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->formatStateUsing(fn(\) => number_format((float)(\ ?? 0), 0, ',', '.') . ' ₫')
                    ->sortable(),
                Tables\Columns\TextColumn::make('details_count')
                    ->label('Items')
                    ->counts('details'),
                Tables\Columns\TextColumn::make('status')
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->isPending()),

                // Xác nhận nhập kho trực tiếp từ danh sách
                Tables\Actions\Action::make('confirm_receipt')
                    ->label('Xác nhận')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Xác nhận nhập kho?')
                    ->modalDescription('Hành động này sẽ cộng số lượng vào tồn kho và không thể hoàn tác.')
                    ->visible(fn ($record) => $record->isPending())
                    ->action(function ($record) {
                        foreach ($record->details as $detail) {
                            $detail->product?->increment('stock', $detail->quantity);
                            $detail->update(['remaining_quantity' => $detail->quantity]);
                        }
                        $record->update(['status' => 'completed']);
                        \App\Services\ActivityLogService::log(
                            'confirm_goods_receipt',
                            "Đã xác nhận nhập kho phiếu #{$record->id}.",
                            'inventory', $record,
                            ['total_amount' => $record->total_amount]
                        );
                        \Filament\Notifications\Notification::make()
                            ->success()->title('Nhập kho thành công')->send();
                    }),

                // Huỷ phiếu nhập trực tiếp từ danh sách
                Tables\Actions\Action::make('cancel_receipt')
                    ->label('Huỷ')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Huỷ phiếu nhập?')
                    ->modalDescription('Phiếu sẽ bị huỷ. Tồn kho không thay đổi.')
                    ->visible(fn ($record) => $record->isPending())
                    ->action(function ($record) {
                        $record->update(['status' => 'cancelled']);
                        \Filament\Notifications\Notification::make()
                            ->warning()->title('Đã huỷ phiếu nhập')->send();
                    }),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListGoodsReceipts::route('/'),
            'create' => Pages\CreateGoodsReceipt::route('/create'),
            'edit' => Pages\EditGoodsReceipt::route('/{record}/edit'),
            'view' => Pages\ViewGoodsReceipt::route('/{record}'),
        ];
    }
}
