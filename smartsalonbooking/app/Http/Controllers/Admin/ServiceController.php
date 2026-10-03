<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::with('category')
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.services.index', [
            'services' => $services,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', [
            'service' => new Service,
            'categories' => Category::orderBy('name')->get(),
            'employees' => Employee::active()->orderBy('name')->get(),
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);
        $service->employees()->sync($data['employees'] ?? []);

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service): View
    {
        $service->load('employees:id');

        return view('admin.services.form', [
            'service' => $service,
            'categories' => Category::orderBy('name')->get(),
            'employees' => Employee::active()->orderBy('name')->get(),
        ]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);
        $service->employees()->sync($data['employees'] ?? []);

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->appointments()->exists()) {
            return back()->with('error', 'Cannot delete a service that has bookings. Set it to inactive instead.');
        }

        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return back()->with('success', 'Service deleted.');
    }
}
