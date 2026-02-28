<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-legacy-data';
    protected $description = 'Import data from the old pure PHP project database into the new unified Laravel schemas.';

    public function handle()
    {
        $this->info('Starting legacy data import...');

        // 1. Map old table names to category names
        $categoryMap = [
            'dienthoai' => 'Điện Thoại',
            'laptop' => 'Laptop',
            'tablet' => 'Tablet',
            'smartwatch' => 'Smartwatch',
            'phukien' => 'Phụ Kiện',
        ];

        // 2. Seed Categories
        $this->info('Seeding Categories...');
        foreach ($categoryMap as $key => $name) {
            \App\Models\Category::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                ['name' => $name]
            );
        }

        // 3. Migrate Products & Brands
        $this->info('Migrating Products & Brands...');
        $legacyDb = \Illuminate\Support\Facades\DB::connection('mysql_legacy');

        $productIdMap = []; // old_table -> [old_id => new_id]

        foreach ($categoryMap as $oldTable => $catName) {
            $this->info("Processing table: {$oldTable}");
            $category = \App\Models\Category::where('slug', \Illuminate\Support\Str::slug($catName))->first();

            try {
                $rows = $legacyDb->table($oldTable)->get();
            } catch (\Exception $e) {
                $this->warn("Skipping table {$oldTable} (Error: " . $e->getMessage() . ")");
                continue;
            }

            foreach ($rows as $row) {
                // Ensure brand exists
                $brand = null;
                if (!empty($row->brand)) {
                    $brand = \App\Models\Brand::firstOrCreate(
                        ['slug' => \Illuminate\Support\Str::slug($row->brand)],
                        ['name' => $row->brand]
                    );
                }

                $product = \App\Models\Product::updateOrCreate(
                    [
                        'name' => $row->name,
                        'category_id' => $category->id,
                    ],
                    [
                        'price' => $row->price,
                        'image' => $row->image ?? null,
                        'description' => $row->description ?? null,
                        'screen' => $row->screen ?? null,
                        'chip' => $row->chip ?? null,
                        'cameraorsensors' => $row->cameraorsensors ?? null,
                        'battery' => $row->battery ?? null,
                        'os' => $row->os ?? null,
                        'brand_id' => $brand ? $brand->id : null,
                    ]
                );

                $productIdMap[$oldTable][$row->id] = $product->id;
            }
        }

        // 4. Migrate Users
        $this->info('Migrating Users...');
        try {
            $oldUsers = $legacyDb->table('users')->get();
            foreach ($oldUsers as $oldUser) {
                \App\Models\User::updateOrCreate(
                    ['username' => $oldUser->username],
                    [
                        'password' => $oldUser->password, // Already bcrypt in legacy
                        'role' => $oldUser->role == 'admin' ? 'admin' : 'user',
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->warn("Skipping users (Error: " . $e->getMessage() . ")");
        }

        // 5. Migrate Orders & Details
        $this->info('Migrating Orders & Order Details...');
        try {
            $oldOrders = $legacyDb->table('orders')->get();

            foreach ($oldOrders as $oldOrder) {

                // map user
                $oldLegacyUser = $legacyDb->table('users')->where('id', $oldOrder->user_id)->first();
                $newUser = $oldLegacyUser ? \App\Models\User::where('username', $oldLegacyUser->username)->first() : null;

                // map status
                $statusMap = [
                    'Chờ xử lý' => 'pending',
                    'Đã xác nhận' => 'confirmed',
                    'Đang giao hàng' => 'shipping',
                    'Thành công' => 'delivered',
                    'Đã hủy' => 'cancelled',
                ];
                $status = $statusMap[$oldOrder->status] ?? 'pending';

                $order = \App\Models\Order::firstOrCreate(
                    // assume combined uniqueness on user_id and order_date
                    [
                        'user_id' => $newUser ? $newUser->id : null,
                        'order_date' => $oldOrder->order_date,
                    ],
                    [
                        'total' => $oldOrder->total,
                        'shipping_name' => $oldOrder->shipping_name,
                        'shipping_address' => $oldOrder->shipping_address,
                        'shipping_phone' => $oldOrder->shipping_phone,
                        'status' => $status,
                    ]
                );

                // Now Order details
                $oldDetails = $legacyDb->table('order_details')->where('order_id', $oldOrder->id)->get();
                foreach ($oldDetails as $oldDetail) {
                    $newProductId = $productIdMap[$oldDetail->product_table][$oldDetail->product_id] ?? null;

                    \App\Models\OrderDetail::firstOrCreate(
                        [
                            'order_id' => $order->id,
                            'product_id' => $newProductId,
                        ],
                        [
                            'quantity' => $oldDetail->quantity,
                            'price_at_purchase' => $oldDetail->price,
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            $this->warn("Skipping orders (Error: " . $e->getMessage() . ")");
        }

        $this->info('Legacy Data Import Completed!');
    }
}
