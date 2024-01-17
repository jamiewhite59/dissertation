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
        return Inertia::render('Events/Index', [
            'name' => 'Jamie'
        ]);
    }

    public function create(Request $request): Response {
        return Inertia::render('Events/Create');
    }

    public function store(Request $request): RedirectResponse {
        // save event to database
        $event = new Event;
        $event->title = $request->title;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->icon = $request->icon;

        $event->save();
        //then
        return redirect('/events');
    }
}
