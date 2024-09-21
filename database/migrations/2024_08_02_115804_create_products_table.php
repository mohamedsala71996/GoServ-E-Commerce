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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // This will be translatable
            $table->json('description'); // This will be translatable
            $table->json('details'); // This will be translatable
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');
            $table->decimal('weight', 8, 2); // Weight with two decimal points
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
// $table->json('main_photos'); // Ensure this is text or json
// $table->foreignId('color_id')->nullable()->constrained('colors')->onDelete('set null'); // main color
// $table->boolean('is_sold')->default(false);
// $table->foreignId('size_id')->nullable()->constrained('sizes')->onDelete('set null'); // Foreign key to sizes table
// $table->integer('main_color_quantity')->default(1);
// $table->decimal('price', 8, 2);
