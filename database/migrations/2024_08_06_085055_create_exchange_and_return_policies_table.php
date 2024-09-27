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
        Schema::create('exchange_and_return_policies', function (Blueprint $table) {
            $table->id();
            $table->json('description'); // This will be translatable
            $table->enum('status', ['active', 'inactive'])->default('inactive'); // Add status enum with default value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_and_return_policies');
    }
};
