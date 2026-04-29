<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $title = 'Lịch sử đánh giá';

    protected static ?string $modelLabel = 'Đánh giá';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                TextColumn::make('product.name')
                    ->label('Sản phẩm')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rating')
                    ->label('Sao')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state == 3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('comment')
                    ->label('Nội dung')
                    ->limit(50)
                    ->searchable(),
                IconColumn::make('is_hidden')
                    ->label('Đã ẩn')
                    ->boolean()
                    ->trueIcon('heroicon-m-eye-slash')
                    ->falseIcon('heroicon-m-eye')
                    ->color(fn (bool $state): string => $state ? 'danger' : 'success'),
                TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Action::make('toggle_visibility')
                    ->label(fn (Review $record): string => $record->is_hidden ? 'Hiện đánh giá' : 'Ẩn đánh giá')
                    ->icon(fn (Review $record): string => $record->is_hidden ? 'heroicon-m-eye' : 'heroicon-m-eye-slash')
                    ->color(fn (Review $record): string => $record->is_hidden ? 'success' : 'danger')
                    ->action(function (Review $record) {
                        $record->is_hidden = !$record->is_hidden;
                        $record->save();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
