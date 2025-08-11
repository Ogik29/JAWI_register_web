<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        Transaction::create([
            'contingent_id' => 1,
            'total' => 500000,
            'date' => now(),
            'foto_invoice' => 'invoice1.jpg'
        ]);
    }
}
