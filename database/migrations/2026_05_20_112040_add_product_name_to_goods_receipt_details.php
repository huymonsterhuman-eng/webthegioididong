<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('goods_receipt_details', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('product_id');
        });

        // Backfill từ products (kể cả soft-deleted)
        \Illuminate\Support\Facades\DB::statement("
            UPDATE goods_receipt_details grd
            JOIN products p ON grd.product_id = p.id
            SET grd.product_name = p.name
        ");
    }

    public function down(): void
    {
        Schema::table('goods_receipt_details', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
    }
};
