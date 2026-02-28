<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('order_date');
            $table->decimal('total', 10, 2);
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address', 500)->nullable();
            $table->string('shipping_phone', 50)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
