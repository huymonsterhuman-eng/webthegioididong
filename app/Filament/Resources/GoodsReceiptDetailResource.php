<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoodsReceiptDetailResource\Pages;
use App\Models\GoodsReceiptDetail;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptDetailResource extends Resource
{
    protected static ?string $model = GoodsReceiptDetail::class;

    protected static ?string $navigationGroup = '🏭 Kho & Vận chuyển (Logistics)';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Quản lý Lô hàng';
    protected static ?string $modelLabel = 'Lô hàng';
    protected static ?string $pluralModelLabel = 'Quản lý Lô hàng';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Bảng này chỉ xem, không dùng form edit
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Mã lô')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => "LÔ-#" . str_pad($state, 5, '0', STR_PAD_LEFT)),
                
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Sản phẩm')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('goods_receipt_id')
                    ->label('Thuộc Phiếu nhập')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => "PN-#" . $state)
                    ->url(fn ($record) => GoodsReceiptResource::getUrl('view', ['record' => $record->goods_receipt_id]))
                    ->color('primary'),

                Tables\Columns\TextColumn::make('import_price')
                    ->label('Giá nhập lô')
                    ->money('VND')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Số lượng gốc')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\TextColumn::make('remaining_quantity')
                    ->label('Tồn kho lô')
                    ->sortable()
                    ->alignRight()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        (int)$state === 0 => 'danger',
                        (int)$state < 5 => 'warning',
                        default => 'success',
                    }),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->getStateUsing(fn ($record) => $record->remaining_quantity > 0 ? 'Còn hàng' : 'Hết hàng')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Còn hàng' ? 'success' : 'danger'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày nhập')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\Filter::make('available')
                    ->label('Chỉ hiện lô còn hàng')
                    ->query(fn (Builder $query): Builder => $query->where('remaining_quantity', '>', 0)),
                Tables\Filters\Filter::make('sold_out')
                    ->label('Chỉ hiện lô hết hàng')
                    ->query(fn (Builder $query): Builder => $query->where('remaining_quantity', '=', 0)),
            ])
            ->actions([
                // Remove view/edit for now, or point to an activity relation manager in the future.
            ])
            ->bulkActions([
                // No bulk actions for batch management
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Can add ActivityLogsRelationManager here later
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoodsReceiptDetails::route('/'),
            // 'create' => Pages\CreateGoodsReceiptDetail::route('/create'),
            // 'view' => Pages\ViewGoodsReceiptDetail::route('/{record}'),
            // 'edit' => Pages\EditGoodsReceiptDetail::route('/{record}/edit'),
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

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
