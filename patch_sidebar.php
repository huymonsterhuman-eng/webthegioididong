<?php

$resources = [
    'ProductResource' => [
        'group' => '📦 Sản phẩm (Catalog)',
        'icon' => 'heroicon-o-device-phone-mobile',
        'label' => 'Sản phẩm',
        'pluralLabel' => 'Sản phẩm',
        'sort' => 1
    ],
    'CategoryResource' => [
        'group' => '📦 Sản phẩm (Catalog)',
        'icon' => 'heroicon-o-tag',
        'label' => 'Danh mục',
        'pluralLabel' => 'Danh mục',
        'sort' => 2
    ],
    'BrandResource' => [
        'group' => '📦 Sản phẩm (Catalog)',
        'icon' => 'heroicon-o-check-badge',
        'label' => 'Thương hiệu',
        'pluralLabel' => 'Thương hiệu',
        'sort' => 3
    ],
    'OrderResource' => [
        'group' => '🛒 Kinh doanh (Sales)',
        'icon' => 'heroicon-o-shopping-bag',
        'label' => 'Đơn hàng',
        'pluralLabel' => 'Đơn hàng',
        'sort' => 1
    ],
    'VoucherResource' => [
        'group' => '🛒 Kinh doanh (Sales)',
        'icon' => 'heroicon-o-ticket',
        'label' => 'Mã giảm giá',
        'pluralLabel' => 'Mã giảm giá',
        'sort' => 2
    ],
    'ReviewResource' => [
        'group' => '🛒 Kinh doanh (Sales)',
        'icon' => 'heroicon-o-star',
        'label' => 'Đánh giá',
        'pluralLabel' => 'Đánh giá',
        'sort' => 3
    ],
    'GoodsReceiptResource' => [
        'group' => '🏭 Kho & Vận chuyển (Logistics)',
        'icon' => 'heroicon-o-document-duplicate',
        'label' => 'Phiếu nhập kho',
        'pluralLabel' => 'Phiếu nhập kho',
        'sort' => 1
    ],
    'SupplierResource' => [
        'group' => '🏭 Kho & Vận chuyển (Logistics)',
        'icon' => 'heroicon-o-truck',
        'label' => 'Nhà cung cấp',
        'pluralLabel' => 'Nhà cung cấp',
        'sort' => 2
    ],
    'ShippingProviderResource' => [
        'group' => '🏭 Kho & Vận chuyển (Logistics)',
        'icon' => 'heroicon-o-paper-airplane',
        'label' => 'Đơn vị vận chuyển',
        'pluralLabel' => 'Đơn vị vận chuyển',
        'sort' => 3
    ],
    'PartnerResource' => [
        'group' => '🏭 Kho & Vận chuyển (Logistics)',
        'icon' => 'heroicon-o-hand-thumb-up',
        'label' => 'Đối tác',
        'pluralLabel' => 'Đối tác',
        'sort' => 4
    ],
    'PostResource' => [
        'group' => '📝 Nội dung (Content)',
        'icon' => 'heroicon-o-document-text',
        'label' => 'Bài viết',
        'pluralLabel' => 'Bài viết',
        'sort' => 1
    ],
    'BannerResource' => [
        'group' => '📝 Nội dung (Content)',
        'icon' => 'heroicon-o-photo',
        'label' => 'Banner quảng cáo',
        'pluralLabel' => 'Banner quảng cáo',
        'sort' => 2
    ],
    'UserResource' => [
        'group' => '🔐 Hệ thống (System)',
        'icon' => 'heroicon-o-users',
        'label' => 'Người dùng',
        'pluralLabel' => 'Người dùng',
        'sort' => 1
    ],
    'RoleResource' => [
        'group' => '🔐 Hệ thống (System)',
        'icon' => 'heroicon-o-shield-check',
        'label' => 'Vai trò & Quyền',
        'pluralLabel' => 'Vai trò & Quyền',
        'sort' => 2
    ],
];

foreach ($resources as $resource => $config) {
    $file = __DIR__ . "/app/Filament/Resources/{$resource}.php";
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);

    // Remove old properties
    $content = preg_replace('/^\s*protected static \?string \$navigationIcon\b.+?;\n/m', '', $content);
    $content = preg_replace('/^\s*protected static \?string \$navigationGroup\b.+?;\n/m', '', $content);
    $content = preg_replace('/^\s*protected static \?string \$navigationLabel\b.+?;\n/m', '', $content);
    $content = preg_replace('/^\s*protected static \?string \$modelLabel\b.+?;\n/m', '', $content);
    $content = preg_replace('/^\s*protected static \?string \$pluralModelLabel\b.+?;\n/m', '', $content);
    $content = preg_replace('/^\s*protected static \?int \$navigationSort\b.+?;\n/m', '', $content);

    // Add new ones
    $props = "\n";
    $props .= "    protected static ?string \$navigationGroup = '{$config['group']}';\n";
    $props .= "    protected static ?int \$navigationSort = {$config['sort']};\n";
    $props .= "    protected static ?string \$navigationIcon = '{$config['icon']}';\n";
    $props .= "    protected static ?string \$navigationLabel = '{$config['label']}';\n";
    $props .= "    protected static ?string \$modelLabel = '{$config['label']}';\n";
    $props .= "    protected static ?string \$pluralModelLabel = '{$config['pluralLabel']}';\n";

    $content = preg_replace('/(protected static \?string \$model\s*=\s*.+?;)/', "$1\n$props", $content);

    file_put_contents($file, $content);
    echo "Updated $file\n";
}
