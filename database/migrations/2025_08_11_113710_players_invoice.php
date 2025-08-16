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
        Schema::create('players_invoice', function (Blueprint $table) {
            $table->id();
            $table->string('foto_invoice')->nullable(); // simpan path foto invoice
            $table->decimal('total_price', 15, 2); // simpan harga total
            $table->date('date'); // tanggal invoice
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_player');
    }
};
