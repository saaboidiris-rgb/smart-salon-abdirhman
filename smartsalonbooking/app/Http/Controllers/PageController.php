<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Handles every "informational" public page: services list, gallery,
 * pricing, team, testimonials and the contact form. Booking has its own
 * BookingController because it has real business logic behind it.
 */
class PageController extends Controller
{
    public function services(Request $request): View
    {
        $services = Service::active()
            ->with('category')
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->integer('category')))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search').'%'))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('services', [
            'services' => $services,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function serviceShow(Service $service): View
    {
        $service->load(['category', 'employees' => fn ($q) => $q->active()]);

        return view('service-show', [
            'service' => $service,
            'related' => Service::active()->where('category_id', $service->category_id)
                ->where('id', '!=', $service->id)->take(3)->get(),
        ]);
    }

    public function gallery(Request $request): View
    {
        $images = Gallery::when($request->filled('category'), fn ($q) => $q->where('category', $request->string('category')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('gallery', [
            'images' => $images,
            'categories' => Gallery::query()->select('category')->distinct()->whereNotNull('category')->pluck('category'),
        ]);
    }

    public function pricing(): View
    {
        return view('pricing', [
            'categories' => Category::with(['services' => fn ($q) => $q->active()->orderBy('name')])->get(),
        ]);
    }

    public function team(): View
    {
        return view('team', [
            'employees' => Employee::active()->with('services')->orderBy('name')->get(),
        ]);
    }

    public function testimonials(): View
    {
        return view('testimonials', [
            'testimonials' => Testimonial::active()->latest()->paginate(9),
        ]);
    }

    public function contact(): View
    {
        return view('contact');
    }

    /**
     * We don't persist contact messages to the database (no table for it in
     * the current schema) - this simply validates the input and shows a
     * success message, giving you a ready-made spot to plug in Laravel Mail
     * or a "contact_messages" table later.
     */
    public function submitContact(ContactRequest $request): RedirectResponse
    {
        $request->validated();

        return back()->with('success', "Thanks for reaching out! We'll get back to you within 24 hours.");
    }
}
