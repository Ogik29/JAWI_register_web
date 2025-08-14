<?php
// 6. player_categories table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('player_categories', function (Blueprint $table) {
            $table->id();
            $table->string('filter')->nullable();
            $table->string('category');
            $table->string('range')->nullable();
            $table->string('type')->nullable();
            $table->foreignId('class_category_id')->constrained('class_categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_categories');
    }
};
