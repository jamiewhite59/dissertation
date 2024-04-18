<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;

class EventCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $event = Event::find('00000000-0000-0000-0000-000000000000');
        $event->customers()->attach('00000000-0000-0000-0000-1');
        $event->customers()->attach('00000000-0000-0000-0000-2');

        $event = Event::find('00000000-0000-0000-0000-000000000001');
        $event->customers()->attach('00000000-0000-0000-0000-7');
        $event->customers()->attach('00000000-0000-0000-0000-3');

        $event = Event::find('00000000-0000-0000-0000-000000000002');
        $event->customers()->attach('00000000-0000-0000-0000-8');
        $event->customers()->attach('00000000-0000-0000-0000-9');

        $event = Event::find('00000000-0000-0000-0000-000000000003');
        $event->customers()->attach('00000000-0000-0000-0000-2');
        $event->customers()->attach('00000000-0000-0000-0000-0');
    }
}
