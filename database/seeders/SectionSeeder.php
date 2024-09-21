<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('sections')->insert([
            ['name' => 'Sec1', 'order' => 1],
            ['name' => 'Sec2', 'order' => 2],
            ['name' => 'Sec3', 'order' => 3],
            ['name' => 'Sec4', 'order' => 4],
            ['name' => 'Sec5', 'order' => 5],
            ['name' => 'Sec6', 'order' => 6],
        ]);

    }
}
