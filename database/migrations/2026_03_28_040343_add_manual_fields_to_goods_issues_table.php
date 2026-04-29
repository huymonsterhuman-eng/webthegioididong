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
        Schema::table('goods_issues', function (Blueprint $table) {
            $table->enum('type', ['auto', 'manual'])->default('auto')->after('id');
            $table->foreignId('order_id')->nullable()->change();
            $table->text('note')->nullable()->after('status');
            $table->foreignId('author_id')->nullable()->after('note')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_issues', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['type', 'note', 'author_id']);
            $table->foreignId('order_id')->nullable(false)->change();
        });
    }
};
