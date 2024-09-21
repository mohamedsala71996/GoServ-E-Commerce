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
        Schema::create('shipping_weights', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_weight', 8, 2);
            $table->decimal('max_weight', 8, 2);
            $table->decimal('additional_rate', 8, 2); // Additional rate per weight unit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_weights');
    }
};
