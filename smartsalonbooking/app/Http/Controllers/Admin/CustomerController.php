<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::with('user')
            ->withCount('appointments')
            ->whereHas('user', function ($q) use ($request) {
                $q->when($request->filled('search'), fn ($qq) => $qq
                    ->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%'));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', ['customers' => $customers]);
    }

    public function show(Customer $customer): View
    {
        $customer->load(['user', 'appointments.service', 'appointments.employee']);

        return view('admin.customers.show', ['customer' => $customer]);
    }

    public function edit(Customer $customer): View
    {
        $customer->load('user');

        return view('admin.customers.edit', ['customer' => $customer]);
    }

    public function update(AdminCustomerUpdateRequest $request, Customer $customer): RedirectResponse
    {
        $data = $request->validated();

        $customer->user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);

        $customer->update([
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated.');
    }
}
