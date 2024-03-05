<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerFormRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request): Response {
        $customers = Customer::all();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
        ]);
    }

    public function create(Request $request): Response {
        return Inertia::render('Customers/Customer');
    }

    public function store(CustomerFormRequest $request): RedirectResponse {
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->company = $request->company ?: '';
        $customer->save();

        return redirect('/customers');
    }

    public function edit(Request $request, $id): Response {
        $customer = Customer::find($id);
        $customer->events = $customer->events;

        return Inertia::render('Customers/Customer', [
            'customer' => $customer,
        ]);
    }

    public function update(CustomerFormRequest $request, $id): RedirectResponse {
        $customer = Customer::find($id);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->company = $request->company;
        $customer->save();

        return redirect('/customers');
    }

    public function destroy(Request $request, $id): RedirectResponse {
        Customer::destroy($id);

        return redirect('/customers');
    }
}
