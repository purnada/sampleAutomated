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
        $provinces = file_get_contents(database_path('provinces.sql'));
        DB::unprepared($provinces);
        $districts = file_get_contents(database_path('districts.sql'));
        DB::unprepared($districts);
        $municipalities = file_get_contents(database_path('municipalities.sql'));
        DB::unprepared($municipalities);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
