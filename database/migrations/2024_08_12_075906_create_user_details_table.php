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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('city_id'); // Foreign key for cities table
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number');
            $table->string('country');
            $table->string('state');
            $table->text('address');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade'); // Foreign key for city
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
