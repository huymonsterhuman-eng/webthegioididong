<?php

namespace App\Filament\Resources;

use App\Filament\Traits\HasResourcePermission;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class UserResource extends Resource
{
    use HasResourcePermission;
    protected static string $requiredPermission = 'view_users';
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = '🔐 Hệ thống (System)';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Người dùng';
    protected static ?string $modelLabel = 'Người dùng';
    protected static ?string $pluralModelLabel = 'Người dùng';

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin tài khoản')
                    ->description('Các thông tin đăng nhập và phân quyền hệ thống.')
                    ->schema([
                        TextInput::make('username')
                            ->label('Tên đăng nhập')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (string $context): bool => $context === 'edit')
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (string $context): bool => $context === 'edit')
                            ->dehydrated(false),
                        TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->hidden(fn (string $context): bool => $context === 'edit')
                            ->maxLength(255)
                            ->helperText('Để trống nếu không muốn đổi mật khẩu (khi cập nhật).'),
                        Select::make('status')
                            ->label('Trạng thái')
                            ->options([
                                'active' => 'Active',
                                'banned' => 'Banned',
                                'unverified' => 'Unverified',
                            ])
                            ->required()
                            ->default('active'),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->label('Vai trò (Roles)')
                            ->helperText('Vai trò quyết định quyền hạn truy cập Admin.'),
                    ])->columns(2),

                Section::make('Thông tin cá nhân')
                    ->description('Chi tiết hồ sơ người dùng ghi nhận từ phía khách hàng.')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Họ tên đầy đủ')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Số điện thoại')
                            ->tel()
                            ->maxLength(20),
                        Select::make('gender')
                            ->label('Giới tính')
                            ->options([
                                'male' => 'Nam',
                                'female' => 'Nữ',
                                'other' => 'Khác',
                            ]),
                        DatePicker::make('birthday')
                            ->label('Ngày sinh'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->label('Tên đăng nhập')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('full_name')
                    ->label('Họ tên')
                    ->searchable()
                    ->placeholder('Chưa cập nhật'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->placeholder('N/A'),
                TextColumn::make('roles.name')
                    ->label('Vai trò')
                    ->badge()
                    ->separator(',')
                    ->color('primary'),
                TextColumn::make('orders_count')
                    ->label('Đơn hàng')
                    ->counts('orders')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'banned' => 'danger',
                        'unverified' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày đăng ký')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'banned' => 'Banned',
                        'unverified' => 'Unverified',
                    ]),
                Tables\Filters\Filter::make('new_this_month')
                    ->label('New registrations this month')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn(User $record): string => $record->status === 'banned' ? 'Unlock' : 'Lock')
                    ->color(fn(User $record): string => $record->status === 'banned' ? 'success' : 'danger')
                    ->icon(fn(User $record): string => $record->status === 'banned' ? 'heroicon-m-lock-open' : 'heroicon-m-lock-closed')
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->status = $record->status === 'banned' ? 'active' : 'banned';
                        $record->save();
                    })
                    ->visible(fn () => auth()->user()->can('manage_users')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('activate_bulk')
                        ->label('Kích hoạt hàng loạt')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'active']))
                        ->visible(fn () => auth()->user()->can('manage_users')),
                    BulkAction::make('ban_bulk')
                        ->label('Chặn hàng loạt')
                        ->icon('heroicon-m-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['status' => 'banned']))
                        ->visible(fn () => auth()->user()->can('manage_users')),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Thông tin định danh')
                    ->schema([
                        Infolists\Components\TextEntry::make('username')
                            ->label('Tên đăng nhập')
                            ->weight('bold')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Trạng thái')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'active' => 'success',
                                'banned' => 'danger',
                                'unverified' => 'warning',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('roles.name')
                            ->label('Vai trò hệ thống')
                            ->badge()
                            ->color('primary'),
                    ])->columns(4),

                Infolists\Components\Section::make('Hồ sơ người dùng')
                    ->schema([
                        Infolists\Components\TextEntry::make('full_name')
                            ->label('Họ và tên')
                            ->placeholder('Chưa cập nhật'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Số điện thoại')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('gender')
                            ->label('Giới tính')
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'male' => 'Nam',
                                'female' => 'Nữ',
                                'other' => 'Khác',
                                default => 'Chưa rõ',
                            }),
                        Infolists\Components\TextEntry::make('birthday')
                            ->label('Ngày sinh')
                            ->date('d/m/Y')
                            ->placeholder('N/A'),
                    ])->columns(4),

                Infolists\Components\Section::make('Thống kê hoạt động')
                    ->schema([
                        Infolists\Components\TextEntry::make('orders_count')
                            ->label('Tổng số đơn hàng')
                            ->state(fn(User $record): int => $record->orders()->count()),
                        Infolists\Components\TextEntry::make('total_spent')
                            ->label('Tổng chi tiêu')
                            ->state(fn(User $record): string => number_format($record->orders()->where('status', '!=', 'cancelled')->sum('total')) . ' VND'),
                        Infolists\Components\TextEntry::make('reviews_count')
                            ->label('Số đánh giá')
                            ->state(fn(User $record): int => $record->reviews()->count()),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Ngày gia nhập')
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(4),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
            RelationManagers\AddressesRelationManager::class,
            RelationManagers\ReviewsRelationManager::class,
            RelationManagers\VouchersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
