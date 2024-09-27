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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->nullable()->onDelete('cascade'); // User who asked the question
            $table->text('question'); // Question comment
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status field with default value
            $table->enum('topic', [
                'Terms and Conditions',
                'Privacy Policy',
                'Return and Exchange Policy'
            ]); // Enum for topics
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
