<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalOffersTable extends Migration
{
    public function up()
    {
        Schema::create('global_offers', function (Blueprint $table) {
            $table->id();
            $table->decimal('discount_percentage', 5, 2);
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable(); // Allow null if no default value is needed
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('global_offers');
    }
}
