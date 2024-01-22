<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function show(Request $request): Response {
        $customers = Customer::all();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
        ]);
    }

    public function create(Request $request): Response {
        return Inertia::render('Customers/Create');
    }

    public function store(Request $request): RedirectResponse {
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->company = $request->company;
        $customer->save();

        return redirect('/customers');
    }

    public function edit(Request $request, $id): Response {
        $customer = Customer::find($id);

        return Inertia::render('Customers/Create', [
            'customer' => $customer,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse {
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
