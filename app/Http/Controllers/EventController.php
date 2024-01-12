<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function show(Request $request): Response {
        return Inertia::render('Events/Landing', [
            'name' => 'Jamie'
        ]);
    }

    public function create(Request $request): RedirectResponse {
        $event = new Event;
        $event->title = 'Test Event 1';
        $event->start_date = Carbon::today();
        $event->end_date = Carbon::today();
        $event->icon = 'fa-plus';

        $event->save();

        return redirect('/events');
    }
}
