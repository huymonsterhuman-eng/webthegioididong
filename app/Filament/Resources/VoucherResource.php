<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VoucherResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'view_vouchers';
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationGroup = '🛒 Kinh doanh (Sales)';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Mã giảm giá';
    protected static ?string $modelLabel = 'Mã giảm giá';
    protected static ?string $pluralModelLabel = 'Mã giảm giá';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin Voucher')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Mã Voucher')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Loại giảm giá')
                            ->options([
                                'fixed' => 'Giảm số tiền cố định',
                                'percent' => 'Giảm theo phần trăm',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Mức giảm (VNĐ hoặc %)')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(fn (\Filament\Forms\Get $get) => $get('type') === 'percent' ? 100 : null),
                        Forms\Components\TextInput::make('min_order_value')
                            ->label('Giá trị đơn hàng tối thiểu (VNĐ)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        Forms\Components\TextInput::make('max_discount')
                            ->label('Mức giảm tối đa (VNĐ, chỉ dùng cho giảm %)')
                            ->numeric()
                            ->nullable()
                            ->minValue(0),
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Ngày hết hạn')
                            ->nullable(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Kích hoạt')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Mã')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Loại')
                    ->formatStateUsing(fn (string $state): string => $state === 'fixed' ? 'Số tiền' : 'Phần trăm')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->label('Mức giảm')
                    ->formatStateUsing(function (string $state, Voucher $record): string {
                        if ($record->type === 'percent') {
                            return rtrim(rtrim(number_format($state, 2, ',', '.'), '0'), ',') . '%';
                        }
                        return number_format($state, 0, ',', '.') . ' ₫';
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Ngày hết hạn')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Đã dùng')
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Khả dụng')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
