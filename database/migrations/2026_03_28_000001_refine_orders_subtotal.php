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
        // Precisely calculate subtotal from order_details
        // We use a join or subquery to ensure subtotal = sum(price * quantity)
        $orders = DB::table('orders')->get();
        
        foreach ($orders as $o) {
            $sum = DB::table('order_details')
                ->where('order_id', $o->id)
                ->sum(DB::raw('price_at_purchase * quantity'));
            
            if ($sum > 0) {
                DB::table('orders')->where('id', $o->id)->update(['subtotal' => $sum]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
