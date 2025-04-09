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
        Schema::create('setting_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('protocol')->nullable();
            $table->string('parameter')->nullable();
            $table->string('host_name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('encryption')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_emails');
    }
};
