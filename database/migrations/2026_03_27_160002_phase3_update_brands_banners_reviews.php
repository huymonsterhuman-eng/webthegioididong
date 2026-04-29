<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Brands: add is_active and description
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('logo');
            }
            if (!Schema::hasColumn('brands', 'description')) {
                $table->text('description')->nullable()->after('is_active');
            }
        });

        // Banners: add scheduling columns and type
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'start_date')) {
                $table->date('start_date')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('banners', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('banners', 'type')) {
                $table->enum('type', ['carousel', 'popup', 'sidebar'])->default('carousel')->after('end_date');
            }
        });

        // Reviews: add order_detail link and helpful_count; change single image to json
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'order_detail_id')) {
                $table->foreignId('order_detail_id')->nullable()
                    ->constrained('order_details')->nullOnDelete()
                    ->after('product_id');
            }
            if (!Schema::hasColumn('reviews', 'helpful_count')) {
                $table->unsignedInteger('helpful_count')->default(0)->after('admin_reply');
            }
        });

        // 1. Sanitize existing image data: convert "image.jpg" to "[\"image.jpg\"]"
        // 2. Rename image → images and change to JSON
        // Only perform if the old 'image' column still exists
        if (Schema::hasColumn('reviews', 'image') && !Schema::hasColumn('reviews', 'images')) {
            \DB::transaction(function () {
                // Only update if the image doesn't look like JSON already (doesn't start with [ or {)
                \DB::statement("UPDATE reviews SET `image` = CONCAT('[\"', `image`, '\"]') 
                               WHERE `image` IS NOT NULL 
                               AND `image` != '' 
                               AND `image` NOT LIKE '[%' 
                               AND `image` NOT LIKE '{%'");
                
                \DB::statement("ALTER TABLE reviews CHANGE `image` `images` JSON NULL");
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reviews', 'images')) {
            \DB::statement("ALTER TABLE reviews CHANGE `images` `image` VARCHAR(255) NULL");
        }

        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'order_detail_id')) {
                $table->dropForeign(['order_detail_id']);
                $table->dropColumn(['order_detail_id']);
            }
            if (Schema::hasColumn('reviews', 'helpful_count')) {
                $table->dropColumn(['helpful_count']);
            }
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(array_filter(['start_date', 'end_date', 'type'], function ($col) {
                return Schema::hasColumn('banners', $col);
            }));
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn(array_filter(['is_active', 'description'], function ($col) {
                return Schema::hasColumn('brands', $col);
            }));
        });
    }
};
