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
        Schema::create('candidate_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('applying_for');
            $table->string('referred_by')->nullable();
            $table->string('name');
            $table->string('father_or_husband_name');
            $table->string('mobile_1');
            $table->string('mobile_2')->nullable();
            $table->string('email')->nullable();
            $table->date('dob');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('education');
            $table->string('job_experience');
            $table->string('resident_type');
            $table->string('traveling_mode');
            $table->text('address_1');
            $table->text('address_2');
            $table->string('landmark');
            $table->string('city');
            $table->string('pin_code');
            $table->string('last_company')->nullable();
            $table->string('last_designation')->nullable();
            $table->string('last_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_registrations');
    }
};