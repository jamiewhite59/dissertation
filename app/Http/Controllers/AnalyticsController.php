<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Customer;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    public function index(Request $request): Response {
        $items = Item::all();
        $customers = Customer::all();
        $items = $items->map(function($item) {
            $item->events = $item->events();
            return $item;
        });
        $customers = $customers->map(function($customer) {
            $customer->events = $customer->events;
            return $customer;
        });

        return Inertia::render('Analytics/Index', [
            'items' => $items,
            'customers' => $customers,
        ]);
    }
}
