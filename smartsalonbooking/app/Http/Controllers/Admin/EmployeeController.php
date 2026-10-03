<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $employees = Employee::withCount('appointments')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.employees.index', ['employees' => $employees]);
    }

    public function create(): View
    {
        return view('admin.employees.form', [
            'employee' => new Employee,
            'services' => Service::active()->orderBy('name')->get(),
        ]);
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee = Employee::create($data);
        $employee->services()->sync($data['services'] ?? []);

        return redirect()->route('admin.employees.index')->with('success', 'Employee added.');
    }

    public function edit(Employee $employee): View
    {
        $employee->load('services:id');

        return view('admin.employees.form', [
            'employee' => $employee,
            'services' => Service::active()->orderBy('name')->get(),
        ]);
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);
        $employee->services()->sync($data['services'] ?? []);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        if ($employee->appointments()->exists()) {
            return back()->with('error', 'Cannot delete an employee with existing bookings. Set them to inactive instead.');
        }

        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return back()->with('success', 'Employee removed.');
    }
}
