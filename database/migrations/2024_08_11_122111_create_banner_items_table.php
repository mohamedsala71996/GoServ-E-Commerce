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
        Schema::create('banner_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->constrained()->onDelete('cascade');
            $table->json('title');
            $table->json('description');
            $table->string('link')->nullable();
            $table->string('photo');
            $table->integer('order')->default(0); // Order of items in the slider
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_items');
    }
};
