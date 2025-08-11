<?php
// 8. transaction table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contingent_id')->constrained('contingent')->cascadeOnDelete();
            $table->decimal('total', 15, 2);
            $table->date('date');
            $table->string('foto_invoice')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transaction');
    }
};
