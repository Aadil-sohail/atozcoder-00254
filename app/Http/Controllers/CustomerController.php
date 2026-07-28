<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view customers')->only('index');
        $this->middleware('permission:create customers')->only('store');
        $this->middleware('permission:edit customers')->only('update');
        $this->middleware('permission:delete customers')->only('destroy');
    }

    public function index(): View
    {
        $customers = Customer::orderBy('name')->get();

        return view('customers.index', compact('customers'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());

        return redirect()->route('customers.index')->with('status', 'Customer created successfully.');
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index')->with('status', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('status', 'Customer deleted successfully.');
    }
}
