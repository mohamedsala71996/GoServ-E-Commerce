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
        Schema::create('product_color_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_color_id')->constrained('product_colors')->onDelete('cascade'); // Updated column name
            $table->foreignId('size_id')->nullable()->constrained('sizes')->onDelete('set null'); // Foreign key to sizes table
            $table->integer('quantity')->default(1); // Quantity for each size of the color
            $table->decimal('price', 8, 2);
            $table->decimal('cost', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_color_size');
    }
};
