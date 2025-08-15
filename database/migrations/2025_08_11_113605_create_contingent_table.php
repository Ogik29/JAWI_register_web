<?php
// 4. contingent table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contingent', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('manajer_name');
            $table->string('email')->nullable();
            $table->string('no_telp')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->timestamps();
            $table->integer('status')->default(0); // 1 = aktif, 0 = tidak aktif, 2 = ditolak
        });
    }

    public function down(): void {
        Schema::dropIfExists('contingent');
    }
};
