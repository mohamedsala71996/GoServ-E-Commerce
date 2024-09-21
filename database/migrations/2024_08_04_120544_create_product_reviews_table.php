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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullable()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Ensure product_id exists in products table
            $table->integer('rating')->unsigned(); // Rating field (unsigned integer)
            $table->text('comment')->nullable(); // Comment field (nullable text)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status field with default value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
