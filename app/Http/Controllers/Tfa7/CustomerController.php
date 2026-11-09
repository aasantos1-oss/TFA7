<?php

namespace App\Http\Controllers\Tfa7;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('tfa7.customers.index', ['customers' => Customer::latest('id')->paginate(10)]);
    }

    public function create(): View
    {
        return view('tfa7.customers.form', ['customer' => new Customer]);
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($this->validated($request));
        return redirect()->route('tfa7.customers.index')->with('success', 'Customer created.');
    }

    public function edit(Customer $customer): View
    {
        return view('tfa7.customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validated($request));
        return redirect()->route('tfa7.customers.index')->with('success', 'Customer updated.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);
    }
}
