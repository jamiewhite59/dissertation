<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            'id' => Str::uuid()->toString(),
            'name' => Str::random(10),
            'email' => Str::random(10) .  '@test.com',
            'phone_number' => '07934061662',
            'company' => 'Jamies Company'
        ]);
    }
}
