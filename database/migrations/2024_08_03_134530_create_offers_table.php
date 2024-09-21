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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_color_size_id')->constrained('product_color_sizes')->onDelete('cascade'); // Updated column name
            $table->decimal('discount_percentage', 5, 2);
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable(); // Allow null if no default value is needed
            $table->boolean('is_active')->default(true);
            $table->foreignId('global_offer_id')->nullable()->constrained('global_offers')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
