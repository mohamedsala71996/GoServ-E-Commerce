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
        Schema::create('paymob_fees', function (Blueprint $table) {
            $table->id();
            $table->string('card_type'); // e.g., 'Mada', 'Visa', 'Mastercard'
            $table->decimal('percentage_fee', 5, 2); // e.g., 1.00% or 2.75%
            $table->decimal('fixed_fee', 8, 2)->default(0); // e.g., 1.50 SAR
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymob_fees');
    }
};
