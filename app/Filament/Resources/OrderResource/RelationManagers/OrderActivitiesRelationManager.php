<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\ActivityLog;

class OrderActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities'; // Need to define this in Order Model

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Thời gian')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Người thực hiện')
                    ->default('Hệ thống'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Nội dung hoạt động')
                    ->wrap(),
                Tables\Columns\TextColumn::make('action')
                    ->label('Hành động')
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
