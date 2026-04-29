<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class VouchersRelationManager extends RelationManager
{
    protected static string $relationship = 'vouchers';

    protected static ?string $title = 'Kho Voucher';

    protected static ?string $modelLabel = 'Voucher';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('code')
                    ->label('Mã')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('name')
                    ->label('Tên Voucher')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('pivot.is_used')
                    ->label('Trạng thái')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->pivot->is_used ? 'Đã dùng' : 'Chưa dùng')
                    ->color(fn(string $state): string => match ($state) {
                        'Đã dùng' => 'success',
                        'Chưa dùng' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('pivot.used_at')
                    ->label('Ngày dùng')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('N/A'),
                TextColumn::make('expires_at')
                    ->label('Hết hạn')
                    ->dateTime('d/m/Y')
                    ->color(fn($record) => $record->expires_at && $record->expires_at->isPast() ? 'danger' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                // Read-only
            ])
            ->bulkActions([
                //
            ]);
    }
}
