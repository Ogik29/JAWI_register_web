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
        Schema::table('kelas', function (Blueprint $table) {
            $table->integer('jumlah_pemain')->default(1);; 
            // sesuaikan 'nama' dengan kolom terakhir sebelum kamu ingin menaruh field ini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_pertandingan', function (Blueprint $table) {
            $table->dropColumn('jumlah_pemain');
        });
    }
};
