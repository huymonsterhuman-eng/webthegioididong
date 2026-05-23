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
        // Snapshot tên nhà cung cấp vào goods_receipts
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->string('supplier_name')->nullable()->after('supplier_id');
        });

        // Snapshot tên đơn vị vận chuyển vào orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_provider_name')->nullable()->after('partner_id');
        });

        // Backfill dữ liệu cũ
        \Illuminate\Support\Facades\DB::statement("
            UPDATE goods_receipts gr
            JOIN partners p ON gr.supplier_id = p.id
            SET gr.supplier_name = p.name
        ");
        \Illuminate\Support\Facades\DB::statement("
            UPDATE orders o
            JOIN partners p ON o.partner_id = p.id
            SET o.shipping_provider_name = p.name
        ");
    }

    public function down(): void
    {
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->dropColumn('supplier_name');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_provider_name');
        });
    }
};
