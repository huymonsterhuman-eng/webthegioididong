<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 'preparing' vào ENUM status của bảng orders
        DB::statement("ALTER TABLE orders MODIFY COLUMN status
            ENUM('pending','confirmed','preparing','shipping','delivered','cancelled')
            NOT NULL DEFAULT 'pending'");

        // Thêm cột preparing_at để tracking thời gian chuyển kho
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('preparing_at')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('preparing_at');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN status
            ENUM('pending','confirmed','shipping','delivered','cancelled')
            NOT NULL DEFAULT 'pending'");
    }
};
