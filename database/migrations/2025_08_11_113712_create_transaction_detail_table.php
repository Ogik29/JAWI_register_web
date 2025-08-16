<?php
// 9. transaction_detail table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaction_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_invoice_id')->constrained('players_invoice')->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transaction_detail');
    }
};
