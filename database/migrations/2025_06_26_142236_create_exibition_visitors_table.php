<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exibition_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('event_venue')->nullable();
            $table->string('jeweller_name');
            $table->string('owner_name');
            $table->string('email')->nullable();
            $table->string('mobile_1');
            $table->string('mobile_2')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('enquired_for');
            $table->string('headway_service')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exibition_visitors');
    }
};
