<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransactionDetail;

class TransactionDetailSeeder extends Seeder
{
    public function run(): void
    {
        TransactionDetail::create([
            'transaction_id' => 1,
            'player_id' => 1,
            'price' => 500000
        ]);
    }
}
