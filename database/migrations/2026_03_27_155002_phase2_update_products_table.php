<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Rename unclear column name if it exists
            if (Schema::hasColumn('products', 'cameraorsensors')) {
                $table->renameColumn('cameraorsensors', 'camera');
            }

            // Add inventory/catalog fields if they don't exist
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('slug');
            }
            if (!Schema::hasColumn('products', 'weight')) {
                $table->integer('weight')->nullable()->after('stock')
                    ->comment('Weight in grams, used for shipping fee calculation');
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('weight');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'camera')) {
                $table->renameColumn('camera', 'cameraorsensors');
            }
            $table->dropColumn(array_filter(['sku', 'weight', 'is_featured'], function ($col) {
                return Schema::hasColumn('products', $col);
            }));
        });
    }
};
