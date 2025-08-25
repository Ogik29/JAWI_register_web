<?php
// 4. contingent table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contingent', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('manajer_name');
            $table->string('email')->nullable();
            $table->string('no_telp')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->timestamps();
            $table->integer('status')->default(0); //  0 = tidak aktif, 1 = verif pembayaran, 2 = ditolak, 3 = verif data
            $table->string('surat_rekomendasi')->nullable();
            $table->text('catatan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contingent');
    }
};
