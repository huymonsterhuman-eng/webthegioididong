<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the new product_images table if it doesn't exist
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('path');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        }

        // Migrate existing JSON images data into the new table if the column still exists
        if (Schema::hasColumn('products', 'images')) {
            $products = \DB::table('products')
                ->whereNotNull('images')
                ->where('images', '!=', 'null')
                ->where('images', '!=', '[]')
                ->select('id', 'images', 'image')
                ->get();

            foreach ($products as $product) {
                $images = json_decode($product->images, true);
                if (!is_array($images)) continue;

                foreach ($images as $index => $path) {
                    if (empty($path)) continue;
                    
                    // Check if already migrated to avoid duplicates if rerun
                    $exists = \DB::table('product_images')
                        ->where('product_id', $product->id)
                        ->where('path', $path)
                        ->exists();

                    if (!$exists) {
                        \DB::table('product_images')->insert([
                            'product_id' => $product->id,
                            'path' => $path,
                            'sort_order' => $index,
                            'is_primary' => ($path === $product->image) ? 1 : 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Drop the images json column from products
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('images');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('products', 'images')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('images')->nullable();
            });
        }
        Schema::dropIfExists('product_images');
    }
};
