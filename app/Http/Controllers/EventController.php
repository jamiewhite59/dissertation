<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventFormRequest;
use App\Models\Event;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function show(Request $request): Response {
        $events = Event::all();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }

    public function create(Request $request): Response {
        return Inertia::render('Events/Create');
    }

    public function store(EventFormRequest $request): RedirectResponse {
        $event = new Event;
        $event->title = $request->title;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->icon = $request->icon;
        $event->save();

        return redirect('/events');
    }

    public function edit(Request $request, $id): Response {
        $event = Event::find($id);
        $customers = Customer::all();

        return Inertia::render('Events/Create', [
            'event' => $event,
            'customers' => $customers,
        ]);
    }

    public function update(EventFormRequest $request, $id): RedirectResponse {
        $event = Event::find($id);

        $event->title = $request->title;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->icon = $request->icon;
        $event->save();

        return redirect('/events');
    }

    public function destroy(Request $request, $id): RedirectResponse {
        Event::destroy($id);

        return redirect('/events');
    }
}
