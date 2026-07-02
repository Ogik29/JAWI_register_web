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
        Schema::table('pertandingan', function (Blueprint $table) {
            $table->unsignedInteger('unit1_id')->nullable()->after('match_number');
            $table->unsignedInteger('unit2_id')->nullable()->after('unit1_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertandingan', function (Blueprint $table) {
            $table->dropColumn(['unit1_id', 'unit2_id']);
        });
    }
};
