<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = collect(User::all()->modelKeys());


        for ($i = 0; $i < 100; $i++) {
            $data = [];
            for ($v = 0; $v < 5000; $v++) {
                $data[] = [
                    'from' => $users->random(),
                    'to' => $users->random(),
                    'amount' => rand(1, 200),
                    'status' => random_int(0, 1),
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];

            }

            $chunks = array_chunk($data, 1000);
            foreach ($chunks as $chunk) {
                Transaction::insert($chunk);
            }
        }

    }
}
