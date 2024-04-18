<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            'id' => '00000000-0000-0001-0000-000000000000',
            'title' => 'Computers',
        ]);
        DB::table('categories')->insert([
            'id' => '00000000-0000-0002-0000-000000000000',
            'title' => 'Chargers',
        ]);
        DB::table('categories')->insert([
            'id' => '00000000-0000-0003-0000-000000000000',
            'title' => 'Mice',
        ]);
        DB::table('categories')->insert([
            'id' => '00000000-0000-0004-0000-000000000000',
            'title' => 'Phones',
        ]);
    }
}
