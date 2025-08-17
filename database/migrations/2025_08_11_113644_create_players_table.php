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
            // $table->string('jenis_pertandingan')->nullable();
            $table->foreignId('player_category_id')->nullable()->constrained('player_categories')->cascadeOnDelete();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_diri')->nullable();
            $table->string('foto_persetujuan_ortu')->nullable();
            $table->integer('status')->default(0); // 0 belum bayar, 1 pending, 2 verifikasi, 3 ditolak

            $table->date('tgl_lahir')->nullable();
            $table->foreignId('kelas_pertandingan_id')->constrained('kelas_pertandingan')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
