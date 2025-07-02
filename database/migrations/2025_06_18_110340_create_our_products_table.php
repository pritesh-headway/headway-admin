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
        Schema::create('our_products', function (Blueprint $table) {
            $table->id();
            $table->string('photo');
            $table->string('product_banner');
            $table->string('title');
            $table->text('desc');
            $table->text('tagline')->nullable();
            $table->string('play_store')->nullable();
            $table->string('app_store')->nullable();
            $table->string('web_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('our_products');
    }
};
