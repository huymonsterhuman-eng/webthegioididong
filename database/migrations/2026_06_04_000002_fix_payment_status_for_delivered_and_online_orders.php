<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Đơn đã giao nhưng payment_status vẫn unpaid → paid
        DB::table('orders')
            ->where('status', 'delivered')
            ->where('payment_status', 'unpaid')
            ->update(['payment_status' => 'paid']);

        // 2. Đơn đang giao/chuẩn bị bằng VNPay hoặc MoMo (đã thanh toán online) → paid
        DB::table('orders')
            ->whereIn('status', ['shipping', 'preparing'])
            ->whereIn('payment_method', ['vnpay', 'momo'])
            ->where('payment_status', 'unpaid')
            ->update(['payment_status' => 'paid']);
    }

    public function down(): void
    {
        // Không rollback data vì không thể biết đơn nào paid thật vs paid do migration
    }
};
