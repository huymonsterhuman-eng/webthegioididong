<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        $product = \App\Models\Product::find($state);
                        if ($product) {
                            $set('price_at_purchase', $product->price);
                            $set('product_name', $product->name);
                            $set('product_image', $product->image);
                        }
                    }),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->required(),
                Forms\Components\TextInput::make('price_at_purchase')
                    ->label('Price')
                    ->numeric()
                    ->required(),
                Forms\Components\Hidden::make('product_name'),
                Forms\Components\Hidden::make('product_image'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->columns([
                Tables\Columns\ImageColumn::make('product_image')
                    ->label('Image')
                    ->getStateUsing(function ($record) {
                        if (empty($record->product_image))
                            return url('storage/img/placeholder.jpg');
                        $img = $record->product_image;
                        if (str_starts_with($img, 'http'))
                            return $img;
                        if (str_starts_with($img, 'img/'))
                            return url('storage/' . $img);
                        return \Illuminate\Support\Facades\Storage::url($img);
                    })
                    ->square()
                    ->extraImgAttributes(['loading' => 'lazy', 'class' => 'rounded']),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_at_purchase')
                    ->label('Price')
                    ->numeric(0, ',', '.')
                    ->suffix(' ₫')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->getStateUsing(fn($record) => $record->quantity * $record->price_at_purchase)
                    ->numeric(0, ',', '.')
                    ->suffix(' ₫'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Read-only
            ])
            ->actions([
                // Read-only
            ])
            ->bulkActions([
                // Read-only
            ]);
    }
}
