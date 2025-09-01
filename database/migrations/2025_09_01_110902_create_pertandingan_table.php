<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertandinganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pertandingan', function (Blueprint $table) {
            // Kolom Primary Key
            $table->id();

            // Foreign Key untuk kelas pertandingan
            $table->unsignedBigInteger('kelas_pertandingan_id');

            // Informasi dasar bracket
            $table->integer('round_number')->comment('Nomor babak, cth: 1, 2, 3 untuk Babak Penyisihan, Perempat Final, Semi Final');
            $table->integer('match_number')->comment('Nomor urut pertandingan dalam satu babak');

            // Foreign Keys untuk para pemain
            $table->unsignedBigInteger('player1_id')->nullable()->comment('Pemain di slot 1');
            $table->unsignedBigInteger('player2_id')->nullable()->comment('Pemain di slot 2');

            // Skor pertandingan
            $table->integer('score1')->nullable();
            $table->integer('score2')->nullable();

            // Foreign Key untuk pemenang
            $table->unsignedBigInteger('winner_id')->nullable()->comment('Pemain yang menang');

            // Kunci untuk struktur bracket (menunjuk ke pertandingan selanjutnya)
            $table->unsignedBigInteger('next_match_id')->nullable()->comment('ID pertandingan selanjutnya untuk pemenang');
            
            // Status pertandingan
            $table->enum('status', ['menunggu_peserta', 'siap_dimulai', 'berlangsung', 'selesai', 'ditunda'])
                  ->default('menunggu_peserta');

            // Timestamps standar (created_at, updated_at)
            $table->timestamps();


            // === DEFINISI FOREIGN KEY CONSTRAINTS ===

            // Relasi ke tabel 'kelas_pertandingan'
            $table->foreign('kelas_pertandingan_id')
                  ->references('id')
                  ->on('kelas_pertandingan')
                  ->onDelete('cascade'); // Jika kelas dihapus, semua pertandingannya juga terhapus

            // Relasi ke tabel 'players' untuk Player 1
            $table->foreign('player1_id')
                  ->references('id')
                  ->on('players')
                  ->onDelete('set null'); // Jika pemain dihapus, slot menjadi kosong (NULL)

            // Relasi ke tabel 'players' untuk Player 2
            $table->foreign('player2_id')
                  ->references('id')
                  ->on('players')
                  ->onDelete('set null');

            // Relasi ke tabel 'players' untuk Pemenang
            $table->foreign('winner_id')
                  ->references('id')
                  ->on('players')
                  ->onDelete('set null');

            // Relasi ke tabel 'pertandingan' itu sendiri (self-referencing)
            $table->foreign('next_match_id')
                  ->references('id')
                  ->on('pertandingan')
                  ->onDelete('set null'); // Jika match berikutnya dihapus, link ini menjadi kosong
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pertandingan');
    }
}