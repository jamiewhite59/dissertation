<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0001-000000000000',
            'title' => 'OptiPlex 7010',
            'description' => 'Dell',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0001-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0002-000000000000',
            'title' => 'Wired Mouse',
            'stock_type' => 'bulk',
            'category_id' => '00000000-0000-0003-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0003-000000000000',
            'title' => 'Lightning Cable',
            'stock_type' => 'bulk',
            'category_id' => '00000000-0000-0002-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0004-000000000000',
            'title' => 'Dell XPS Desktop',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0001-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0005-000000000000',
            'title' => 'Acer Aspire TC',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0001-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0006-000000000000',
            'title' => 'Apple Mac Mini (M2)',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0001-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0007-000000000000',
            'title' => 'Apple iMac (M3)',
            'description' => '24-inch',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0001-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0008-000000000000',
            'title' => 'Alienware Aurora R16',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0001-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0009-000000000000',
            'title' => 'Lenovo Legion 5i',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0001-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0010-000000000000',
            'title' => '16 amp plug',
            'description' => 'USB-C female',
            'stock_type' => 'bulk',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0011-000000000000',
            'title' => 'OptiPlex 7010 Charger',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0002-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0012-000000000000',
            'title' => 'MacBook Pro Charger',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0002-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0013-000000000000',
            'title' => 'Logitech Bluetooth Mouse',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0003-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0014-000000000000',
            'title' => 'Apple Bluetooth Mouse',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0003-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0015-000000000000',
            'title' => 'Logitech MX Master 3 Mouse',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0003-0000-000000000000',
       ]);
       DB::table('items')->insert([
            'id' => '00000000-0000-0000-0016-000000000000',
            'title' => 'iPhone 16',
            'stock_type' => 'hire',
            'category_id' => '00000000-0000-0004-0000-000000000000',
       ]);
    }
}
