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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('shipping_amount', 10, 2)->default(0); // Added shipping amount
            $table->decimal('total_weight', 10, 2);
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'accepted',
                'cancelled',
                'out for delivery',
                'delivered',
                'not received',
                'returned',
                'out for delivery return',
                'delivered return'
            ])->default('pending');
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->string('tracking_number')->unique()->nullable();
            $table->string('source_data_sub_type')->nullable();
            $table->string('transaction_id')->nullable();
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
