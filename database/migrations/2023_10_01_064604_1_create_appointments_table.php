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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->index();
            $table->unsignedBigInteger('booked_by')->index();
            $table->dateTime('appointment_date')->index();
            $table->string('name')->nullable()->index();
            $table->string('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable()->index();
            $table->string('payment_mode')->nullable();
            $table->enum('status', ['0', '1', '2'])->default('0')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
