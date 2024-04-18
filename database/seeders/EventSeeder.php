<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'id' => '00000000-0000-0000-0000-000000000000',
            'title' => 'Office Xmas Party',
            'start_date' => Carbon::parse('Monday next week'),
            'end_date' => Carbon::parse('Thursday next week'),
        ]);
        DB::table('events')->insert([
            'id' => '00000000-0000-0000-0000-000000000001',
            'title' => 'Diversity Day',
            'start_date' => Carbon::parse('2 weeks monday'),
            'end_date' => Carbon::parse('2 weeks thursday'),
        ]);
        DB::table('events')->insert([
            'id' => '00000000-0000-0000-0000-000000000002',
            'title' => 'Random Party',
            'start_date' => Carbon::parse('12am today'),
            'end_date' => Carbon::parse('tomorrow'),
        ]);
        DB::table('events')->insert([
            'id' => '00000000-0000-0000-0000-000000000003',
            'title' => 'Diwali',
            'start_date' => Carbon::parse('last monday'),
            'end_date' => Carbon::parse('last tuesday'),
        ]);
    }
}
