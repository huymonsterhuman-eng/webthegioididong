<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Force Subtotal from Total if subtotal is 0 or null
        DB::statement("UPDATE orders SET subtotal = COALESCE(total, 0.01) WHERE subtotal IS NULL OR subtotal = 0");

        // 2. Force delivered_at from updated_at for delivered orders
        DB::statement("UPDATE orders SET delivered_at = COALESCE(updated_at, NOW()) WHERE status = 'delivered' AND delivered_at IS NULL");

        // 3. Force cancelled_at from updated_at for cancelled orders
        DB::statement("UPDATE orders SET cancelled_at = COALESCE(updated_at, NOW()) WHERE status = 'cancelled' AND cancelled_at IS NULL");

        // 4. Fallback for subtotal from order_details if total is also 0
        $orders = DB::table('orders')->whereNull('subtotal')->orWhere('subtotal', 0)->get();
        foreach ($orders as $o) {
            $subtotal = DB::table('order_details')
                ->where('order_id', $o->id)
                ->sum(DB::raw('price * quantity'));
            
            if ($subtotal > 0) {
                DB::table('orders')->where('id', $o->id)->update(['subtotal' => $subtotal]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse action needed for data backfill
    }
};
