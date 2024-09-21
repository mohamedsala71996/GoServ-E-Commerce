<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymobFeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('paymob_fees')->insert([
            [
                'card_type' => 'Mada',
                'percentage_fee' => 1.00,
                'fixed_fee' => 0.00,
            ],
            [
                'card_type' => 'Visa',
                'percentage_fee' => 2.75,
                'fixed_fee' => 1.50,
            ],
            [
                'card_type' => 'Mastercard',
                'percentage_fee' => 2.75,
                'fixed_fee' => 1.50,
            ],
        ]);
    }
}
