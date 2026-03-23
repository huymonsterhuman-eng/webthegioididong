<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = '🔐 Hệ thống (System)';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Vai trò & Quyền';
    protected static ?string $modelLabel = 'Vai trò & Quyền';
    protected static ?string $pluralModelLabel = 'Vai trò & Quyền';


    public static function canViewAny(): bool
    {
        return Auth::check() && (
            Auth::user()->hasRole('super-admin') ||
            Auth::user()->can('manage_roles')
        );
    }

    public static function form(Form $form): Form
    {
        // Group permissions by prefix for readability
        $allPermissions = Permission::all();

        $grouped = [];
        foreach ($allPermissions as $perm) {
            $parts = explode('_', $perm->name, 2);
            $prefix = $parts[0] ?? 'other';
            $grouped[$prefix][] = $perm->name;
        }

        $sections = [];
        $labelMap = [
            'view'     => '👁  Xem',
            'edit'     => '✏️  Chỉnh sửa',
            'confirm'  => '✅  Xác nhận',
            'manage'   => '⚙️  Quản lý',
            'moderate' => '🛡  Kiểm duyệt',
        ];
        $groupLabels = [
            'dashboard'          => '📊 Dashboard',
            'orders'             => '📦 Đơn hàng (Orders)',
            'products'           => '📱 Sản phẩm (Products)',
            'inventory'          => '🏭 Kho hàng',
            'reviews'            => '⭐ Đánh giá',
            'users'              => '👤 Người dùng',
            'vouchers'           => '🎟  Voucher',
            'banners'            => '🖼  Banner',
            'shipping'           => '🚚 Nhà vận chuyển',
            'suppliers'          => '🏢 Nhà cung cấp',
            'roles'              => '🔑 Phân quyền',
        ];

        // Build full permission list as a single checkbox list grouped by category
        $permissionOptions = $allPermissions->pluck('name', 'name')->toArray();

        return $form->schema([
            Forms\Components\Section::make('Thông tin vai trò')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Tên vai trò')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100)
                        ->helperText('Ví dụ: Nhân viên kho, Nhân viên tư vấn'),
                ])->columns(1),

            Forms\Components\Section::make('Phân quyền (Permissions)')
                ->description('Chọn các quyền cho vai trò này.')
                ->schema([
                    Forms\Components\CheckboxList::make('permissions')
                        ->label('')
                        ->options($permissionOptions)
                        ->relationship('permissions', 'name')
                        ->columns(3)
                        ->gridDirection('row')
                        ->searchable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên vai trò')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Số quyền')
                    ->counts('permissions')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Số người dùng')
                    ->counts('users')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tạo lúc')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Role $record) {
                        if ($record->name === 'super-admin') {
                            \Filament\Notifications\Notification::make()
                                ->title('Không thể xóa vai trò super-admin!')
                                ->danger()
                                ->send();
                            return false;
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
