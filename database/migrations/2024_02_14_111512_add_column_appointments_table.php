<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('visited_type')->nullable();
            $table->string('sector')->nullable();
            $table->unsignedBigInteger('province_id')->index()->nullable();
            $table->unsignedBigInteger('district_id')->index()->nullable();
            $table->unsignedBigInteger('municipality_id')->index()->nullable();
            $table->string('ward_no')->nullable();
            $table->string('house_no')->nullable();
            $table->string('nepali_date');
            $table->bigInteger('clildren')->nullable();
            $table->string('remarks', 500)->nullable();
            $table->string('cancel_by')->nullable();
            $table->string('reschedule_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['visited_type', 'sector_id', 'province_id', 'district_id', 'municipality_id', 'ward_no', 'house_no', 'nepali_date']);
        });
    }
};
