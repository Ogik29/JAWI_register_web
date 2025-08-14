<?php
// 7. players table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('contingent_id')->constrained('contingent')->cascadeOnDelete();
            $table->string('nik');
            $table->string('gender');
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('jenis_pertandingan')->nullable();
            $table->foreignId('player_category_id')->constrained('player_categories')->cascadeOnDelete();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_diri')->nullable();
            $table->string('foto_persetujuan_ortu')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
