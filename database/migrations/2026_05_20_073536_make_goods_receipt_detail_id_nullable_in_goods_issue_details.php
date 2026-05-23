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
        // Drop FK constraint trước, sau đó modify column thành nullable
        Schema::table('goods_issue_details', function (Blueprint $table) {
            $table->dropForeign(['goods_receipt_detail_id']);
            $table->unsignedBigInteger('goods_receipt_detail_id')->nullable()->change();
            $table->foreign('goods_receipt_detail_id')
                  ->references('id')->on('goods_receipt_details')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goods_issue_details', function (Blueprint $table) {
            $table->dropForeign(['goods_receipt_detail_id']);
            $table->unsignedBigInteger('goods_receipt_detail_id')->nullable(false)->change();
            $table->foreign('goods_receipt_detail_id')
                  ->references('id')->on('goods_receipt_details')
                  ->cascadeOnDelete();
        });
    }
};
