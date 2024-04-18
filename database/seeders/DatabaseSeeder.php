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
        $this->call([
            CustomerSeeder::class,
            EventSeeder::class,
            CategorySeeder::class,
            GroupSeeder::class,
            ItemSeeder::class,
            EventCustomerSeeder::class,
            PieceSeeder::class,
            GroupSeeder::class,
            EventItemSeeder::class,
        ]);
    }
}
