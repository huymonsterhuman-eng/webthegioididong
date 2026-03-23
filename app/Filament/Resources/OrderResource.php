<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class OrderResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'view_orders';
    protected static ?string $model = Order::class;

    protected static ?string $navigationGroup = '🛒 Kinh doanh (Sales)';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Đơn hàng';
    protected static ?string $modelLabel = 'Đơn hàng';
    protected static ?string $pluralModelLabel = 'Đơn hàng';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'username')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('created_at')
                            ->disabled(),
                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->prefix('₫')
                            ->disabled(),
                        Forms\Components\Select::make('voucher_id')
                            ->relationship('voucher', 'code')
                            ->label('Voucher Code')
                            ->disabled(),
                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->prefix('₫')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending (Chờ xử lý)',
                                'confirmed' => 'Confirmed (Đã xác nhận)',
                                'shipping' => 'Shipping (Đang giao hàng)',
                                'delivered' => 'Delivered (Đã giao thành công)',
                                'cancelled' => 'Cancelled (Đã hủy)',
                            ])
                            ->required()
                            ->native(false)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('payment_method')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Shipping Details')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_phone')
                            ->disabled(),
                        Forms\Components\TextInput::make('shipping_address')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('shipping_method')
                            ->disabled()
                            ->label('Shipping Method'),
                        Forms\Components\TextInput::make('shipping_fee')
                            ->numeric()
                            ->prefix('₫')
                            ->disabled()
                            ->label('Shipping Fee'),
                        Forms\Components\Select::make('partner_id')
                            ->relationship('partner', 'name')
                            ->label('Shipping Provider')
                            ->disabled(),
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking Number')
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Admin Interface')
                    ->schema([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Internal Admin Note')
                            ->maxLength(1000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric(0, ',', '.')
                    ->suffix(' ₫')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'shipping' => 'warning',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Chờ xử lý',
                        'confirmed' => 'Đã xác nhận',
                        'shipping' => 'Đang giao hàng',
                        'delivered' => 'Đã giao',
                        'cancelled' => 'Đã hủy',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'express' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Provider')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_method')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shipping_name')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'shipping' => 'Shipping',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('confirm_and_ship')
                    ->label('Confirm & Ship')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['pending', 'confirmed']) && (auth()->user()->can('confirm_orders') || auth()->user()->hasRole('super-admin')))
                    ->form([
                        Forms\Components\Select::make('partner_id')
                            ->label('Select Shipping Provider')
                            ->options(fn () => \App\Models\Partner::where('type', 'shipping_provider')->where('is_active', true)->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $trackingNumber = 'VNPost-' . strtoupper(Str::random(8));
                        $record->update([
                            'status' => 'shipping',
                            'partner_id' => $data['partner_id'],
                            'tracking_number' => $trackingNumber,
                        ]);
                        
                        Notification::make()
                            ->title('Order marked as shipping')
                            ->body("Assigned tracking number: {$trackingNumber}")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->visible(fn () => static::canEdit(null))
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending (Chờ xử lý)',
                                'confirmed' => 'Confirmed (Đã xác nhận)',
                                'shipping' => 'Shipping (Đang giao hàng)',
                                'delivered' => 'Delivered (Đã giao thành công)',
                                'cancelled' => 'Cancelled (Đã hủy)',
                            ])
                            ->required()
                            ->default(fn(Order $record) => $record->status),
                    ])
                    ->action(function (Order $record, array $data): void {
                        $record->update(['status' => $data['status']]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateStatuses')
                        ->label('Update Status (Bulk)')
                        ->icon('heroicon-o-pencil-square')
                        ->visible(fn () => static::canEdit(null))
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'confirmed' => 'Confirmed',
                                    'shipping' => 'Shipping',
                                    'delivered' => 'Delivered',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $record->update(['status' => $data['status']]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderDetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
