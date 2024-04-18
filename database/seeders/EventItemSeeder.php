<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-100000000000',
            'item_id' => '00000000-0000-0000-0002-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000000',
            'status' => 'reserved',
        ]);
        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-200000000000',
            'item_id' => '00000000-0000-0000-0002-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000000',
            'status' => 'reserved',
        ]);
        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-300000000000',
            'item_id' => '00000000-0000-0000-0004-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000000',
            'status' => 'reserved',
        ]);

        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-400000000000',
            'item_id' => '00000000-0000-0000-0005-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000001',
            'status' => 'reserved',
        ]);
        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-500000000000',
            'item_id' => '00000000-0000-0000-0005-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000001',
            'status' => 'reserved',
        ]);

        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-600000000000',
            'item_id' => '00000000-0000-0000-0006-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000002',
            'piece_id' => '00000007-0000-0000-0000-000000000000',
            'status' => 'checked-out',
        ]);

        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-700000000000',
            'item_id' => '00000000-0000-0000-0016-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000003',
            'piece_id' => '00000052-0000-0000-0000-000000000000',
            'status' => 'completed',
        ]);
        DB::table('event_items')->insert([
            'id' => '00000000-0000-0000-0000-800000000000',
            'item_id' => '00000000-0000-0000-0016-000000000000',
            'event_id' => '00000000-0000-0000-0000-000000000003',
            'piece_id' => '00000051-0000-0000-0000-000000000000',
            'status' => 'completed',
        ]);
    }
}
