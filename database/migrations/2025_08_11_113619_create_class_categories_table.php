<?php
// 5. class_categories table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender')->nullable();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('jenis_pertandingan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_categories');
    }
};
