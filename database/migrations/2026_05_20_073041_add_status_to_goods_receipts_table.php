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
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->after('note');
        });

        // Đánh dấu các phiếu nhập cũ là completed (đã nhập kho trước khi có status)
        \Illuminate\Support\Facades\DB::table('goods_receipts')->update(['status' => 'completed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_receipts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
