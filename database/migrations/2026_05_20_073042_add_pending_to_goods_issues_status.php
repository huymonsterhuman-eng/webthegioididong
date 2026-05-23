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
        // Thêm 'pending' vào enum status của goods_issues
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE goods_issues MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled') NOT NULL DEFAULT 'completed'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback: xóa pending khỏi enum
        \Illuminate\Support\Facades\DB::statement(
            "ALTER TABLE goods_issues MODIFY COLUMN status ENUM('completed', 'cancelled') NOT NULL DEFAULT 'completed'"
        );
    }
};
