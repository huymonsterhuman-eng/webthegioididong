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
        // Đổi cascadeOnDelete → restrictOnDelete để ngăn xóa supplier khi có phiếu nhập
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->dropForeign('goods_receipts_supplier_id_foreign');
            $table->foreign('supplier_id')
                  ->references('id')->on('partners')
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->foreign('supplier_id')
                  ->references('id')->on('partners')
                  ->cascadeOnDelete();
        });
    }
};
