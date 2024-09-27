<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_amount', 8, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->integer('usage_limit')->nullable(); // Maximum number of times this coupon can be used
            $table->integer('usage_user_limit')->nullable(); // Maximum number of times this coupon can be used
            $table->integer('used_count')->default(0); // Track how many times it has been used
            $table->enum('status', ['active', 'inactive'])->default('active'); // New status column
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
