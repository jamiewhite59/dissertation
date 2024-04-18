<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventFormRequest;
use App\Models\Event;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Group;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(Request $request): Response {
        $events = Event::all();

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }

    public function create(Request $request): Response {
        return Inertia::render('Events/Customer');
    }

    public function store(EventFormRequest $request): RedirectResponse {
        $event = new Event;
        $event->title = $request->title;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->icon = $request->icon ?: '';
        $event->save();

        return redirect("/events/$event->id/edit");
    }

    public function edit(Request $request, $id): Response {
        $event = Event::find($id);
        $event->customers = $event->customers;
        $event->items = $event->eventItems();
        $customers = Customer::all();
        $items = Item::all();
        $items = $items->map(function($item) {
            $item->category = $item->category;
            return $item;
        });
        $items = $items->map(function($item) {
            $item->pieces = $item->pieces;
            return $item;
        });
        $groups = Group::all();
        $groups = $groups->map(function($group) {
            $group->pieces = $group->pieces;
            $group->container = $group->container();
            $group->pieces = $group->pieces->map(function($piece) {
                $piece->item = $piece->item;
                return $piece;
            });
            return $group;
        });
        $categories = Category::all();
        $tempCategory = new Category;
        $tempCategory->id = null;
        $tempCategory->title = 'Uncategorised';
        $categories->push($tempCategory);

        return Inertia::render('Events/Event', [
            'event' => $event,
            'customers' => $customers,
            'items' => $items,
            'groups' => $groups,
            'categories' => $categories,
        ]);
    }

    public function update(EventFormRequest $request, $id): RedirectResponse {
        $event = Event::find($id);

        $event->title = $request->title;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->icon = $request->icon;
        $event->save();

        return redirect()->route('events.index');
    }

    public function destroy(Request $request, $id): RedirectResponse {
        Event::destroy($id);

        return redirect()->route('events.index');
    }

    public function addCustomer(Request $request, $id): RedirectResponse {
        $event = Event::find($id);

        //TODO: Server side validation to prevent a customer being added to the same event twice.
         $eventCustomers = $event->customers;
        foreach ($eventCustomers as &$customer) {
            if ($customer->id == $request->id) {
                abort(500);
            }
        }

        $event->customers()->attach($request->id);

        return redirect()->route('events.edit', $id);
    }

    public function removeCustomer(Request $request, $id): RedirectResponse {
        $event = Event::find($id);

        $event->customers()->detach($request->id);

        return redirect()->route('events.edit', $id);
    }
}
