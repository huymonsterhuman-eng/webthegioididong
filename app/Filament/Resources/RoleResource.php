<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = '🔐 Hệ thống (System)';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Vai trò & Quyền';
    protected static ?string $modelLabel = 'Vai trò & Quyền';
    protected static ?string $pluralModelLabel = 'Vai trò & Quyền';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function canViewAny(): bool
    {
        return Auth::check() && (
            Auth::user()->hasRole('super-admin') ||
            Auth::user()->can('manage_roles')
        );
    }

    public static function getPermissionLabels(): array
    {
        return [
            // Hệ thống
            'access_admin'          => '🔐 Truy cập trang Quản trị',
            'manage_roles'          => '🛡️ Quản lý Vai trò & Quyền hạn',
            'view_reports'          => '📊 Xem Báo cáo & Thống kê',
            'view_system_logs'      => '📜 Nhật ký Hệ thống (Đăng nhập, bảo mật...)',
            'view_activity_logs'    => '🏭 Nhật ký Kho (Nhập/Xuất, tồn kho...)',
            'view_order_logs'       => '🛒 Nhật ký Đơn hàng (Lịch sử xử lý...)',

            // Sản phẩm & Nội dung
            'view_products'         => '👁️ Xem danh sách sản phẩm',
            'create_products'       => '➕ Thêm sản phẩm mới',
            'edit_products'         => '✏️ Sửa thông tin sản phẩm',
            'delete_products'       => '🗑️ Xóa sản phẩm',
            'manage_collections'    => '📂 Quản lý Bộ sưu tập (Collections)',
            'view_categories'       => '👁️ Xem danh mục',
            'manage_categories'     => '⚙️ Quản lý danh mục sản phẩm',
            'view_brands'           => '👁️ Xem thương hiệu',
            'manage_brands'         => '⚙️ Quản lý thương hiệu',
            'manage_banners'        => '🖼️ Quản lý Banners/Quảng cáo',
            'manage_posts'          => '📝 Quản lý Bài viết & Tin tức',

            // Kho & Vận chuyển
            'manage_inventory'      => '📊 Theo dõi & Quản lý tồn kho',
            'manage_goods_receipt'  => '📥 Quản lý Phiếu nhập kho',
            'manage_goods_issue'    => '📤 Quản lý Phiếu xuất kho',
            'manage_suppliers'      => '🏢 Quản lý Nhà cung cấp',
            'manage_shipping'       => '🚚 Quản lý Đơn vị vận chuyển',

            // Bán hàng & Khách hàng
            'view_orders'           => '👁️ Xem danh sách đơn hàng',
            'manage_orders'         => '⚙️ Xử lý/Cập nhật đơn hàng',
            'view_vouchers'         => '🎟️ Xem mã giảm giá',
            'manage_vouchers'       => '⚙️ Quản lý mã giảm giá (Vouchers)',
            'view_reviews'          => '⭐ Xem đánh giá khách hàng',
            'manage_reviews'        => '⚙️ Quản lý/Duyệt đánh giá',
            'view_users'            => '👥 Xem danh sách người dùng',
            'manage_users'          => '⚙️ Quản lý thông tin người dùng',
            'view_partners'         => '🤝 Xem danh sách đối tác',
            'manage_partners'       => '⚙️ Quản lý đối tác kinh doanh',
        ];
    }

    public static function getPermissionGroups(): array
    {
        return [
            'Sản phẩm & Nội dung' => [
                'view_products', 'create_products', 'edit_products', 'delete_products',
                'manage_collections', 'view_categories', 'manage_categories',
                'view_brands', 'manage_brands', 'manage_banners', 'manage_posts'
            ],
            'Kho & Vận chuyển' => [
                'manage_inventory', 'manage_goods_receipt',
                'manage_goods_issue', 'manage_suppliers', 'manage_shipping'
            ],
            'Bán hàng & Khách hàng' => [
                'view_orders', 'manage_orders', 'view_vouchers', 'manage_vouchers',
                'view_reviews', 'manage_reviews', 'view_users', 'manage_users',
                'view_partners', 'manage_partners'
            ],
            'Hệ thống & Nhật ký' => [
                'access_admin', 'manage_roles', 'view_reports',
                'view_system_logs', 'view_activity_logs', 'view_order_logs'
            ],
        ];
    }

    public static function form(Form $form): Form
    {
        $allPermissions = Permission::all();
        $labels = static::getPermissionLabels();
        $groups = static::getPermissionGroups();

        $tabSchema = [];
        foreach ($groups as $groupName => $perms) {
            $options = [];
            foreach ($perms as $perm) {
                if ($allPermissions->contains('name', $perm)) {
                    $options[$perm] = $labels[$perm] ?? $perm;
                }
            }

            if (!empty($options)) {
                // Use unique name for each tab's checkbox list to avoid state conflict
                $fieldName = 'permissions_' . str_replace([' ', '&'], ['_', ''], $groupName);

                $tabSchema[] = Forms\Components\Tabs\Tab::make($groupName)
                    ->schema([
                        Forms\Components\CheckboxList::make($fieldName)
                            ->label('')
                            ->options($options)
                            ->columns(2)
                            ->gridDirection('row')
                            ->searchable()
                            ->live() // Live for real-time summary updates
                            ->dehydrated(false), // Saved by the collector hook below
                    ]);
            }
        }

        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Thông tin vai trò')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên vai trò')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(100)
                                    ->disabled(fn (?Role $record) => $record?->name === 'super-admin')
                                    ->helperText(fn (?Role $record) => $record?->name === 'super-admin'
                                        ? 'Không thể đổi tên vai trò hệ thống super-admin.'
                                        : 'Ví dụ: Bán hàng, Thủ kho, Quản lý nội dung'),
                            ]),

                        Forms\Components\Section::make('Phân quyền chi tiết')
                            ->description('Hệ thống sẽ tổng hợp tất cả các quyền được chọn ở các tab bên dưới.')
                            ->icon('heroicon-o-lock-closed')
                            ->schema([
                                // Collector hook for saving relationships
                                Forms\Components\Hidden::make('permissions_sync')
                                    ->dehydrated(false)
                                    ->saveRelationshipsUsing(function (Forms\Components\Hidden $component, $record) use ($groups) {
                                        $allSelected = [];
                                        $livewire = $component->getLivewire();
                                        foreach ($groups as $groupName => $perms) {
                                            $fieldName = 'permissions_' . str_replace([' ', '&'], ['_', ''], $groupName);
                                            $tabSelected = $livewire->data[$fieldName] ?? [];
                                            if (is_array($tabSelected)) {
                                                $allSelected = array_merge($allSelected, $tabSelected);
                                            }
                                        }
                                        $record->syncPermissions(array_unique($allSelected));
                                    }),

                                Forms\Components\Tabs::make('Phân quyền chi tiết')
                                    ->tabs($tabSchema)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('📋 Tóm tắt quyền hạn')
                            ->description('Danh sách quyền đang được chọn')
                            ->schema([
                                Forms\Components\Placeholder::make('selected_permissions_summary')
                                    ->label('')
                                    ->content(function ($get) use ($groups, $labels) {
                                        $groupColors = [
                                            'Sản phẩm & Nội dung'  => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'dark_bg' => 'dark:bg-blue-900',   'dark_text' => 'dark:text-blue-200'],
                                            'Kho & Vận chuyển'     => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dark_bg' => 'dark:bg-yellow-900', 'dark_text' => 'dark:text-yellow-200'],
                                            'Bán hàng & Khách hàng'=> ['bg' => 'bg-green-100',  'text' => 'text-green-800',  'dark_bg' => 'dark:bg-green-900',  'dark_text' => 'dark:text-green-200'],
                                            'Hệ thống & Nhật ký'   => ['bg' => 'bg-red-100',    'text' => 'text-red-800',    'dark_bg' => 'dark:bg-red-900',    'dark_text' => 'dark:text-red-200'],
                                        ];

                                        $totalSelected = 0;
                                        $html = '<div class="space-y-4">';

                                        foreach ($groups as $groupName => $perms) {
                                            $fieldName = 'permissions_' . str_replace([' ', '&'], ['_', ''], $groupName);
                                            $tabSelected = $get($fieldName);
                                            $tabSelected = is_array($tabSelected) ? $tabSelected : [];

                                            if (empty($tabSelected)) continue;

                                            $totalSelected += count($tabSelected);
                                            $color = $groupColors[$groupName] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'dark_bg' => 'dark:bg-gray-700', 'dark_text' => 'dark:text-gray-200'];

                                            $groupBadges = collect($tabSelected)->map(function ($perm) use ($labels, $color) {
                                                $label = $labels[$perm] ?? $perm;
                                                return "<span class='inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {$color['bg']} {$color['text']} {$color['dark_bg']} {$color['dark_text']} mr-1 mb-1'>{$label}</span>";
                                            })->join('');

                                            $count = count($tabSelected);
                                            $html .= "<div>
                                                <p class='text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex justify-between'>
                                                    <span>{$groupName}</span>
                                                    <span class='text-primary-500'>{$count}</span>
                                                </p>
                                                <div class='flex flex-wrap'>{$groupBadges}</div>
                                            </div>";
                                        }

                                        if ($totalSelected === 0) {
                                            return new \Illuminate\Support\HtmlString(
                                                '<div class="py-4 text-center text-gray-400 italic text-sm">Chưa có quyền nào được chọn</div>'
                                            );
                                        }

                                        $footer = "</div><div class='mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-xs font-bold uppercase'>
                                            <span class='text-gray-500'>Tổng cộng:</span>
                                            <span class='text-primary-500 text-lg'>{$totalSelected}</span>
                                        </div>";

                                        return new \Illuminate\Support\HtmlString($html . $footer);
                                    }),
                            ]),

                        Forms\Components\Section::make('Thông tin bản ghi')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Ngày tạo')
                                    ->content(fn (?Role $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Cập nhật lần cuối')
                                    ->content(fn (?Role $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                            ])
                            ->collapsible()
                            ->collapsed(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Thông tin định danh')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Tên vai trò')
                            ->weight('bold')
                            ->placeholder('Chưa đặt tên'),
                        Infolists\Components\TextEntry::make('permissions_count')
                            ->label('Số lượng quyền hạn')
                            ->state(fn ($record): int => $record->permissions()->count())
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('users_count')
                            ->label('Số người dùng đang sở hữu')
                            ->state(fn ($record): int => $record->users()->count())
                            ->badge()
                            ->color('gray'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Ngày tạo')
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(4),

                Infolists\Components\Section::make('Chi tiết bảng quyền hạn')
                    ->schema([
                        Infolists\Components\TextEntry::make('permissions.name')
                            ->label('')
                            ->badge()
                            ->color('success')
                            ->separator(', ')
                            ->limitList(100)
                            ->placeholder('Chưa được phân quyền nào')
                            ->formatStateUsing(function (string $state) {
                                $labels = static::getPermissionLabels();
                                return $labels[$state] ?? $state;
                            }),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view'   => Pages\ViewRole::route('/{record}'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
