<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Collection;

class MigrateCategoriesToCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-categories-to-collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing categories to collections and sync their products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang bắt đầu chuyển đổi Danh mục thành Bộ sưu tập...');

        // Query categories in a way that doesn't load products yet
        $categories = Category::with('parent')->get();
        $count = 0;

        foreach ($categories as $category) {
            $this->info("Đang xử lý: {$category->name}");
            
            // Lấy tên, nếu là danh mục con thì thêm tên danh mục cha phía trước để rõ nghĩa hơn
            $collectionName = $category->parent_id ? $category->parent->name . ' - ' . $category->name : $category->name;

            // Đảm bảo slug không bị trùng
            $collection = Collection::updateOrCreate(
                ['slug' => $category->slug],
                [
                    'name' => $collectionName,
                    'image' => $category->image,
                    'description' => $category->description,
                    'is_active' => true, 
                    'show_on_home' => is_null($category->parent_id), // Root categories show on home
                    'sort_order' => $category->sort_order ?? 0,
                ]
            );

            // Fetch product IDs in chunks or separately to avoid memory issues
            $productIds = \DB::table('products')
                ->where('category_id', $category->id)
                ->pluck('id')
                ->toArray();

            if (!empty($productIds)) {
                $collection->products()->syncWithoutDetaching($productIds);
                $this->info(" - Đã gắn " . count($productIds) . " sản phẩm.");
            }

            $count++;
        }

        $this->info("\nHoàn tất! Đã chuyển đổi " . $count . " danh mục sang bộ sưu tập.");
    }
}
