<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('privacy_policies', function (Blueprint $table) {
            $table->id();
            $table->json('description'); // Translatable description
            $table->enum('status', ['active', 'inactive'])->default('inactive'); // Status enum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('privacy_policies');
    }
};
