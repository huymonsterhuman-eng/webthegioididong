<?php

namespace App\Filament\Resources\CollectionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    protected static ?string $title = 'Sản phẩm trong Bộ sưu tập';
    protected static ?string $modelLabel = 'Sản phẩm';
    protected static ?string $pluralModelLabel = 'Các Sản phẩm';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('primaryImage'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public')
                    ->getStateUsing(function ($record) {
                        $image = $record->primaryImage ? $record->primaryImage->path : $record->image;
                        return $image ?: null;
                    })
                    ->defaultImageUrl(url('storage/img/placeholder.jpg')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->money('VND')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục Gốc'),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Thương hiệu'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Thêm sản phẩm')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name', 'sku']),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Bỏ khỏi Bộ sưu tập'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Xóa các Sản phẩm đã chọn'),
                ]),
            ]);
    }
}
