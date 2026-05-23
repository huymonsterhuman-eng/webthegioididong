<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'view_products';
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = '📦 Sản phẩm (Catalog)';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationLabel = 'Sản phẩm';
    protected static ?string $modelLabel = 'Sản phẩm';
    protected static ?string $pluralModelLabel = 'Sản phẩm';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->suffix(' ₫'),
                Forms\Components\TextInput::make('sale_price')
                    ->numeric()
                    ->suffix(' ₫'),
                Forms\Components\FileUpload::make('image')
                    ->disk('public')
                    ->directory('img')
                    ->image()
                    ->imageEditor()
                    ->columnSpan(1),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('screen')->maxLength(255),
                Forms\Components\TextInput::make('chip')->maxLength(255),
                Forms\Components\TextInput::make('camera')->label('Camera/Cảm biến')->maxLength(255),
                Forms\Components\TextInput::make('sku')->label('SKU (Mã hàng)')->maxLength(100)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('weight')->label('Trọng lượng (gram)')->numeric(),
                Forms\Components\TextInput::make('battery')->maxLength(255),
                Forms\Components\TextInput::make('os')->maxLength(255),
                Forms\Components\TextInput::make('stock')
                    ->label('Stock (Tồn kho)')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('⚠️ Tồn kho chỉ thay đổi qua Phiếu Nhập (Goods Receipts) hoặc khi khách mua hàng.'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Kinh doanh (bật = đang bán, tắt = ngừng kinh doanh)')
                    ->default(true)
                    ->helperText('Tắt sẽ ẩn sản phẩm khỏi giao diện người dùng và form tạo phiếu nhập/xuất mới.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('primaryImage'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public')
                    ->getStateUsing(function ($record) {
                        $image = $record->primaryImage ? $record->primaryImage->path : $record->image;
                        return $image ?: null;
                    })
                    ->defaultImageUrl(url('storage/img/placeholder.jpg'))
                    ->square()
                    ->width(60)
                    ->height(60)
                    ->extraImgAttributes(['loading' => 'lazy', 'class' => 'rounded']),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(['name', 'description']),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric(0, ',', '.')
                    ->suffix(' ₫')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Trạng thái')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Trạng thái kinh doanh')
                    ->trueLabel('Kinh doanh')
                    ->falseLabel('Ngừng kinh doanh'),
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->label('Brand')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->multiple()
                    ->searchable(),
                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('min_price')
                            ->numeric()
                            ->label('Min Price (₫)'),
                        Forms\Components\TextInput::make('max_price')
                            ->numeric()
                            ->label('Max Price (₫)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_price'],
                                fn(Builder $query, $price): Builder => $query->where('price', '>=', $price),
                            )
                            ->when(
                                $data['max_price'],
                                fn(Builder $query, $price): Builder => $query->where('price', '<=', $price),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['min_price'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Min price: ' . number_format($data['min_price']) . '₫')
                                ->removeField('min_price');
                        }
                        if ($data['max_price'] ?? null) {
                            $indicators[] = \Filament\Tables\Filters\Indicator::make('Max price: ' . number_format($data['max_price']) . '₫')
                                ->removeField('max_price');
                        }
                        return $indicators;
                    }),
                Tables\Filters\SelectFilter::make('stock_status')
                    ->label('Stock Status')
                    ->options([
                        'in_stock' => 'In Stock (>0)',
                        'low_stock' => 'Low Stock (5-10)',
                        'critical_stock' => 'Cảnh báo hết hàng (1-4)',
                        'out_of_stock' => 'Sản phẩm hết hàng (=0)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'] ?? null, function (Builder $query, $state) {
                            if ($state === 'in_stock') {
                                return $query->where('stock', '>', 0);
                            }
                            if ($state === 'low_stock') {
                                return $query->whereBetween('stock', [5, 10]);
                            }
                            if ($state === 'critical_stock') {
                                return $query->whereBetween('stock', [1, 4]);
                            }
                            if ($state === 'out_of_stock') {
                                return $query->where('stock', '<=', 0);
                            }
                            return $query;
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, \App\Models\Product $record) {
                        if ($record->stock > 0) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Không thể xóa — Còn tồn kho')
                                ->body("Sản phẩm \"{$record->name}\" còn {$record->stock} đơn vị trong kho. Hãy xuất hết hoặc đặt Ngừng kinh doanh thay vì xóa.")
                                ->send();
                            $action->cancel();
                            return;
                        }
                        if ($record->orderDetails()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Không thể xóa — Có trong đơn hàng')
                                ->body("Sản phẩm \"{$record->name}\" đã có trong lịch sử đơn hàng. Hãy đặt trạng thái Ngừng kinh doanh thay vì xóa.")
                                ->send();
                            $action->cancel();
                            return;
                        }
                        if ($record->goodsReceiptDetails()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Không thể xóa — Có trong phiếu nhập kho')
                                ->body("Sản phẩm \"{$record->name}\" đã xuất hiện trong lịch sử phiếu nhập kho.")
                                ->send();
                            $action->cancel();
                            return;
                        }
                        if ($record->goodsIssueDetails()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Không thể xóa — Có trong phiếu xuất kho')
                                ->body("Sản phẩm \"{$record->name}\" đã xuất hiện trong lịch sử phiếu xuất kho.")
                                ->send();
                            $action->cancel();
                            return;
                        }
                        if ($record->reviews()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('Lưu ý khi xóa')
                                ->body("Sản phẩm có {$record->reviews()->count()} đánh giá — tất cả sẽ bị xóa theo.")
                                ->send();
                        }
                    }),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Tables\Actions\DeleteBulkAction $action, \Illuminate\Database\Eloquent\Collection $records) {
                            $blockedStock   = $records->filter(fn ($p) => $p->stock > 0);
                            $blockedOrders  = $records->filter(fn ($p) => $p->orderDetails()->exists());
                            $blockedReceipt = $records->filter(fn ($p) => $p->goodsReceiptDetails()->exists());
                            $blockedIssue   = $records->filter(fn ($p) => $p->goodsIssueDetails()->exists());
                            $blocked = $blockedStock->merge($blockedOrders)->merge($blockedReceipt)->merge($blockedIssue)->unique('id');
                            if ($blocked->isNotEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->danger()
                                    ->title("Không thể xóa {$blocked->count()} sản phẩm")
                                    ->body('Còn tồn kho / có trong đơn hàng / phiếu kho: ' . $blocked->pluck('name')->join(', '))
                                    ->send();
                                $action->cancel();
                            }
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
