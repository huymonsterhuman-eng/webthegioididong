<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\InventoryResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InventoryResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'manage_inventory';
    protected static ?string $model = Product::class;
    protected static ?string $slug = 'inventory';

    protected static ?string $navigationGroup = '🏭 Kho & Vận chuyển (Logistics)';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Tồn kho';
    protected static ?string $modelLabel = 'Tồn kho SP';
    protected static ?string $pluralModelLabel = 'Tồn kho SP';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // No form needed for inventory view
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->defaultImageUrl(url('storage/img/placeholder.jpg'))
                    ->square()
                    ->width(50)
                    ->height(50)
                    ->extraImgAttributes(['loading' => 'lazy', 'class' => 'rounded']),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Sản phẩm')
                    ->searchable(['name', 'description']),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Tồn kho hiện tại')
                    ->numeric()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state <= 0 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->label('Brand')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('stock_status')
                    ->label('Trạng thái tồn kho')
                    ->options([
                        'in_stock' => 'Còn hàng (>0)',
                        'out_of_stock' => 'Hết hàng (=0)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'] ?? null, function (Builder $query, $state) {
                            if ($state === 'in_stock') {
                                return $query->where('stock', '>', 0);
                            }
                            if ($state === 'out_of_stock') {
                                return $query->where('stock', '<=', 0);
                            }
                            return $query;
                        });
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('sync_inventory')
                    ->label('Đồng bộ lô hàng')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function () {
                        $products = \App\Models\Product::all();
                        $count = 0;
                        foreach ($products as $product) {
                            $stock = (int) $product->stock;
                            \App\Models\GoodsReceiptDetail::where('product_id', $product->id)
                                ->update(['remaining_quantity' => 0]);

                            if ($stock > 0) {
                                $receipts = \App\Models\GoodsReceiptDetail::where('product_id', $product->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                                
                                /** @var \App\Models\GoodsReceiptDetail $receipt */
                                foreach ($receipts as $receipt) {
                                    if ($stock <= 0) break;
                                    $take = min($stock, $receipt->quantity);
                                    $stock -= $take;
                                    $receipt->update(['remaining_quantity' => $take]);
                                }
                            }
                            $count++;
                        }
                        \Filament\Notifications\Notification::make()
                            ->title('Đã đồng bộ ' . $count . ' sản phẩm.')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Chi tiết lô hàng'),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Thông tin sản phẩm')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')->label('Tên sản phẩm'),
                        Infolists\Components\TextEntry::make('brand.name')->label('Thương hiệu'),
                        Infolists\Components\TextEntry::make('category.name')->label('Danh mục'),
                        Infolists\Components\TextEntry::make('stock')->label('Tồn kho hiện tại')
                            ->badge()
                            ->color(fn ($state): string => match (true) {
                                $state <= 0 => 'danger',
                                $state <= 10 => 'warning',
                                default => 'success',
                            }),
                    ])->columns(4),
                
                Infolists\Components\View::make('filament.resources.inventory-resource.pages.view-inventory-batches')
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
            'index' => Pages\ListInventories::route('/'),
            'view' => Pages\ViewInventory::route('/{record}'),
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
