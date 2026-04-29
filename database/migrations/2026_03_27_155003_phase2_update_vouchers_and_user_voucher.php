<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add display name and validity start date to vouchers
        Schema::table('vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('vouchers', 'name')) {
                $table->string('name')->nullable()->after('code');
            }
            if (!Schema::hasColumn('vouchers', 'started_at')) {
                $table->datetime('started_at')->nullable()->after('expires_at');
            }
        });

        // Add audit trail to user_voucher pivot
        Schema::table('user_voucher', function (Blueprint $table) {
            if (!Schema::hasColumn('user_voucher', 'used_at')) {
                $table->timestamp('used_at')->nullable()->after('is_used');
            }
            if (!Schema::hasColumn('user_voucher', 'order_id')) {
                $table->foreignId('order_id')->nullable()
                    ->constrained('orders')
                    ->nullOnDelete()
                    ->after('used_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(array_filter(['name', 'started_at'], function ($col) {
                return Schema::hasColumn('vouchers', $col);
            }));
        });

        Schema::table('user_voucher', function (Blueprint $table) {
            if (Schema::hasColumn('user_voucher', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn(['order_id']);
            }
            if (Schema::hasColumn('user_voucher', 'used_at')) {
                $table->dropColumn(['used_at']);
            }
        });
    }
};
