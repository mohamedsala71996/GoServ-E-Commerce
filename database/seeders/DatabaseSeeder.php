<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(SectionSeeder::class);
        $this->call(SizeSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(PaymobFeesSeeder::class);
        $this->call(ColorsTableSeeder::class);

    }
}
