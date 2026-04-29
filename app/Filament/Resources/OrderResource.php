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
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Get;
use Filament\Forms\Set;

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
                Forms\Components\Grid::make(3)
                    ->schema([
                        // Left Column (Main Information)
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Sản phẩm (Products)')
                                ->schema([
                                    Repeater::make('orderDetails')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\Select::make('product_id')
                                                ->relationship('product', 'name')
                                                ->getOptionLabelFromRecordUsing(fn ($record) => $record->stock > 0 
                                                    ? "{$record->name} (Tồn kho: {$record->stock})" 
                                                    : "{$record->name} (Hết hàng)"
                                                )
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->reactive()
                                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                    $product = \App\Models\Product::find($state);
                                                    if ($product) {
                                                        $set('price_at_purchase', $product->price);
                                                        $set('product_name', $product->name);
                                                        $set('product_image', $product->image);
                                                        // Store stock in a hidden field to use in validation hint
                                                        $set('available_stock', $product->stock);
                                                    }
                                                })
                                                ->columnSpan(3),
                                            
                                            Forms\Components\TextInput::make('quantity')
                                                ->label('Số lượng')
                                                ->numeric()
                                                ->default(1)
                                                ->required()
                                                ->reactive()
                                                ->hint(fn ($get) => $get('available_stock') ? "📦 Tồn kho: {$get('available_stock')}" : null)
                                                ->hintColor('warning')
                                                ->minValue(1)
                                                ->maxValue(fn ($get) => $get('available_stock') ?: 1)
                                                ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get))
                                                ->columnSpan(1),
                                            
                                            Forms\Components\TextInput::make('price_at_purchase')
                                                ->label('Đơn giá')
                                                ->numeric()
                                                ->required()
                                                ->prefix('₫')
                                                ->readonly()
                                                ->reactive()
                                                ->columnSpan(2),

                                            Forms\Components\Hidden::make('product_name'),
                                            Forms\Components\Hidden::make('product_image'),
                                            Forms\Components\Hidden::make('available_stock'),
                                        ])
                                        ->columns(6)
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, Get $get) {
                                            self::updateTotals($set, $get);
                                        })
                                        ->itemLabel(fn (array $state): ?string => $state['product_name'] ?? null),
                                ]),

                            Forms\Components\Section::make('Thông tin giao nhận (Shipping)')
                                ->schema([
                                    Forms\Components\TextInput::make('shipping_name')
                                        ->required()
                                        ->label('Họ tên người nhận'),
                                    Forms\Components\TextInput::make('shipping_phone')
                                        ->required()
                                        ->label('Số điện thoại'),
                                    Forms\Components\TextInput::make('shipping_address')
                                        ->required()
                                        ->label('Địa chỉ chi tiết')
                                        ->columnSpanFull(),
                                    
                                    Forms\Components\Group::make([
                                        Forms\Components\Select::make('shipping_method')
                                            ->label('PT Vận chuyển')
                                            ->options([
                                                'standard' => 'Giao hàng tiêu chuẩn (30k)',
                                                'express' => 'Giao hàng hỏa tốc (50k)',
                                            ])
                                            ->default('standard')
                                            ->live()
                                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                                $fee = $state === 'express' ? 50000 : 30000;
                                                $set('shipping_fee', $fee);
                                                self::updateTotals($set, $get, $fee);
                                            }),
                                        Forms\Components\Select::make('partner_id')
                                            ->relationship(
                                                name: 'partner',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn (Builder $query) => $query->where('type', 'shipping_provider')->where('is_active', true),
                                            )
                                            ->label('Đơn vị vận chuyển')
                                            ->searchable()
                                            ->preload(),
                                    ])->columns(2),
                                ])->columns(2),

                            Forms\Components\Section::make('Ghi chú')
                                ->schema([
                                    Forms\Components\Textarea::make('admin_note')
                                        ->label('Ghi chú của Admin')
                                        ->placeholder('Ghi chú nội bộ cho đơn hàng này...')
                                        ->maxLength(1000)
                                        ->columnSpanFull(),
                                ]),
                        ])->columnSpan(2),

                        // Right Column (Order & Payment Control)
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Khách hàng & Trạng thái')
                                ->schema([
                                    Forms\Components\Select::make('user_id')
                                        ->relationship('user', 'username')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->label('Khách hàng')
                                        ->live()
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('username')->required()->unique(),
                                            Forms\Components\TextInput::make('full_name')->required(),
                                            Forms\Components\TextInput::make('email')->email()->required()->unique(),
                                            Forms\Components\TextInput::make('phone')->tel()->required(),
                                            Forms\Components\TextInput::make('password')->password()->default('password@123')->required(),
                                        ])
                                        ->afterStateUpdated(function ($state, Set $set) {
                                            $user = \App\Models\User::find($state);
                                            if ($user) {
                                                $set('shipping_name', $user->full_name ?: $user->username);
                                                $set('shipping_phone', $user->phone);
                                                
                                                $defaultAddress = $user->addresses()->where('is_default', true)->first();
                                                if ($defaultAddress) {
                                                    $set('shipping_address', $defaultAddress->address);
                                                }
                                            }
                                        }),

                                    Forms\Components\Select::make('status')
                                        ->label('Trạng thái đơn')
                                        ->options([
                                            'pending' => 'Chờ xử lý',
                                            'confirmed' => 'Đã xác nhận',
                                            'shipping' => 'Đang giao hàng',
                                            'delivered' => 'Đã giao thành công',
                                            'cancelled' => 'Đã hủy',
                                        ])
                                        ->default('pending')
                                        ->required()
                                        ->native(false),
                                ]),

                            Forms\Components\Section::make('Thanh toán')
                                ->schema([
                                    Forms\Components\Select::make('payment_method')
                                        ->label('PT Thanh toán')
                                        ->options([
                                            'cod' => 'Thanh toán khi nhận hàng (COD)',
                                            'vnpay' => 'Thanh toán VNPay',
                                            'momo' => 'Thanh toán MoMo',
                                            'manual' => 'Thanh toán thủ công/Khác',
                                        ])
                                        ->default('cod')
                                        ->required()
                                        ->native(false),

                                    Forms\Components\Select::make('payment_status')
                                        ->label('Trạng thái TT')
                                        ->options([
                                            'pending' => 'Chờ thanh toán',
                                            'paid' => 'Đã thanh toán',
                                            'unpaid' => 'Chưa thanh toán (Ghi nợ)',
                                            'failed' => 'Thanh toán thất bại',
                                        ])
                                        ->default('unpaid')
                                        ->required()
                                        ->native(false),
                                ]),

                            Forms\Components\Section::make('Khuyến mãi')
                                ->schema([
                                    Forms\Components\Select::make('voucher_id')
                                        ->relationship('voucher', 'code')
                                        ->label('Mã giảm giá (Voucher)')
                                        ->searchable()
                                        ->live()
                                        ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get)),

                                    Forms\Components\TextInput::make('discount_amount')
                                        ->label('Số tiền giảm')
                                        ->numeric()
                                        ->prefix('₫')
                                        ->default(0)
                                        ->live()
                                        ->readonly(fn ($get) => !empty($get('voucher_id')))
                                        ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get)),
                                ]),

                            Forms\Components\Section::make('Tổng kết chi phí')
                                ->schema([
                                    Forms\Components\TextInput::make('subtotal')
                                        ->label('Tiền hàng')
                                        ->numeric()
                                        ->prefix('₫')
                                        ->readonly()
                                        ->dehydrated()
                                        ->extraAttributes(['class' => 'text-lg font-bold']),
                                    
                                    Forms\Components\TextInput::make('shipping_fee')
                                        ->label('Phí vận chuyển (+)')
                                        ->numeric()
                                        ->prefix('₫')
                                        ->default(0)
                                        ->live()
                                        ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($set, $get)),

                                    Forms\Components\TextInput::make('total')
                                        ->label('Tổng thanh toán')
                                        ->numeric()
                                        ->prefix('₫')
                                        ->readonly()
                                        ->dehydrated()
                                        ->extraAttributes(['class' => 'text-2xl font-black text-primary-600']),
                                ]),
                        ])->columnSpan(1),
                    ])->columns(['lg' => 3]),
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
                    ->label('Tổng cộng')
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
                    ->color(fn (string $state): string => $state === 'express' ? 'success' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('partner.name')
                    ->label('Provider')
                    ->toggleable(),
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    
                    Tables\Actions\Action::make('confirm')
                        ->label('Xác nhận đơn')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Order $record): bool => $record->status === 'pending')
                        ->action(function (Order $record): void {
                            $record->update(['status' => 'confirmed']);

                            Notification::make()
                                ->title('Đã xác nhận đơn hàng')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('ship')
                        ->label('Giao hàng')
                        ->icon('heroicon-o-truck')
                        ->color('warning')
                        ->form([
                            Forms\Components\Select::make('partner_id')
                                ->label('Đơn vị vận chuyển')
                                ->options(fn () => \App\Models\Partner::where('type', 'shipping_provider')->where('is_active', true)->pluck('name', 'id'))
                                ->required(),
                            Forms\Components\TextInput::make('tracking_number')
                                ->label('Mã vận đơn')
                                ->default(fn() => 'SHIP-' . strtoupper(Str::random(10)))
                                ->required(),
                        ])
                        ->visible(fn (Order $record): bool => in_array($record->status, ['pending', 'confirmed']))
                        ->action(function (Order $record, array $data): void {
                            $record->update([
                                'status' => 'shipping',
                                'partner_id' => $data['partner_id'],
                                'tracking_number' => $data['tracking_number'],
                            ]);

                            Notification::make()
                                ->title('Đã chuyển sang trạng thái đang giao hàng')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('delivered')
                        ->label('Đã giao thành công')
                        ->icon('heroicon-o-hand-thumb-up')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (Order $record): bool => $record->status === 'shipping')
                        ->action(function (Order $record): void {
                            $record->update(['status' => 'delivered']);

                            Notification::make()
                                ->title('Đã hoàn thành đơn hàng')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('cancel')
                        ->label('Hủy đơn')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Order $record): bool => in_array($record->status, ['pending', 'confirmed']))
                        ->action(function (Order $record): void {
                            $record->update(['status' => 'cancelled']);

                            Notification::make()
                                ->title('Đã hủy đơn hàng')
                                ->danger()
                                ->send();
                        }),

                    Tables\Actions\Action::make('print_invoice')
                        ->label('In hóa đơn')
                        ->icon('heroicon-o-printer')
                        ->color('info')
                        ->url(fn (Order $record): string => route('admin.orders.invoice', $record), shouldOpenInNewTab: true),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->size(ActionSize::Small)
                ->color('gray')
                ->button()
                ->label('Thao tác'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Read-only
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(3)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Thông tin đơn hàng')
                                ->schema([
                                    Infolists\Components\TextEntry::make('order_code')
                                        ->label('Mã đơn hàng')
                                        ->weight('bold')
                                        ->copyable(),
                                    Infolists\Components\TextEntry::make('status')
                                        ->label('Trạng thái')
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
                                    Infolists\Components\TextEntry::make('created_at')
                                        ->label('Ngày đặt hàng')
                                        ->dateTime('d/m/Y H:i'),
                                    Infolists\Components\TextEntry::make('payment_method')
                                        ->label('PT Thanh toán'),
                                    Infolists\Components\TextEntry::make('payment_status')
                                        ->label('TT Thanh toán')
                                        ->badge()
                                        ->color(fn(string $state): string => match ($state) {
                                            'paid' => 'success',
                                            'unpaid' => 'danger',
                                            default => 'warning',
                                        }),
                                ])->columns(2),

                            Infolists\Components\Section::make('Thông tin vận nhận')
                                ->schema([
                                    Infolists\Components\TextEntry::make('shipping_name')
                                        ->label('Người nhận'),
                                    Infolists\Components\TextEntry::make('shipping_phone')
                                        ->label('Số điện thoại'),
                                    Infolists\Components\TextEntry::make('shipping_address')
                                        ->label('Địa chỉ giao hàng')
                                        ->columnSpanFull(),
                                    Infolists\Components\TextEntry::make('partner.name')
                                        ->label('Đơn vị vận chuyển'),
                                    Infolists\Components\TextEntry::make('tracking_number')
                                        ->label('Mã vận đơn')
                                        ->copyable(),
                                ])->columns(2),
                        ])->columnSpan(2),

                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Chi phí')
                                ->schema([
                                    Infolists\Components\TextEntry::make('subtotal')
                                        ->label('Tiền hàng')
                                        ->numeric(0, ',', '.')
                                        ->suffix(' ₫'),
                                    Infolists\Components\TextEntry::make('discount_amount')
                                        ->label('Giảm giá')
                                        ->color('danger')
                                        ->numeric(0, ',', '.')
                                        ->suffix(' ₫'),
                                    Infolists\Components\TextEntry::make('shipping_fee')
                                        ->label('Phí ship')
                                        ->numeric(0, ',', '.')
                                        ->suffix(' ₫'),
                                    Infolists\Components\TextEntry::make('total')
                                        ->label('Tổng cộng')
                                        ->weight('bold')
                                        ->size('lg')
                                        ->color('success')
                                        ->numeric(0, ',', '.')
                                        ->suffix(' ₫'),
                                ]),

                            Infolists\Components\Section::make('Ghi chú Admin')
                                ->schema([
                                    Infolists\Components\TextEntry::make('admin_note')
                                        ->label('')
                                        ->placeholder('Không có ghi chú.'),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrderDetailsRelationManager::class,
            RelationManagers\OrderActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function updateTotals(Set $set, ?Get $get, $shippingFee = null)
    {
        if (!$get) return;
        
        $subtotal = collect($get('orderDetails'))
            ->map(function ($item) {
                return (float)($item['quantity'] ?? 0) * (float)($item['price_at_purchase'] ?? 0);
            })
            ->sum();

        if ($shippingFee === null) {
            $shippingFee = (float)($get('shipping_fee') ?? 0);
        }
        
        // Handle Voucher Calculation
        $voucherId = $get('voucher_id');
        $discountAmount = (float)($get('discount_amount') ?? 0);

        if ($voucherId) {
            $voucher = \App\Models\Voucher::find($voucherId);
            if ($voucher) {
                $discountAmount = $voucher->calculateDiscount($subtotal);
                $set('discount_amount', $discountAmount);
            }
        }
        
        $set('subtotal', $subtotal);
        $set('total', max(0, $subtotal + $shippingFee - $discountAmount));
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
