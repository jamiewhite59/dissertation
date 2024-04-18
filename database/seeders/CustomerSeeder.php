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
        $characters = ['Michael Scott', 'Dwight Scrute', 'Jim Halpert', 'Pam Beesly', 'Ryan Howard', 'Andy Bernard', 'Robert California', 'Stanley Hudson', 'Kevin Malone', 'Meredith Palmer', 'Angela Martin'];

        foreach ($characters as $key=>$character) {
            $names = explode(" ", $character);
            DB::table('customers')->insert([
                'id' => '00000000-0000-0000-0000-' . $key,
                'name' => $character,
                'email' => $names[0] . '@dundermifflin.com',
                'phone_number' => '123456789',
                'company' => 'Dunder Mifflin',
            ]);
        }
    }
}
