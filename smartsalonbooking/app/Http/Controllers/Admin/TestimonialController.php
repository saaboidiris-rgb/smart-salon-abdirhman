<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', ['testimonials' => Testimonial::latest()->paginate(10)]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', ['testimonial' => new Testimonial]);
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('customer_photo')) {
            $data['customer_photo'] = $request->file('customer_photo')->store('testimonials', 'public');
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial added.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', ['testimonial' => $testimonial]);
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('customer_photo')) {
            if ($testimonial->customer_photo) {
                Storage::disk('public')->delete($testimonial->customer_photo);
            }
            $data['customer_photo'] = $request->file('customer_photo')->store('testimonials', 'public');
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        if ($testimonial->customer_photo) {
            Storage::disk('public')->delete($testimonial->customer_photo);
        }
        $testimonial->delete();

        return back()->with('success', 'Testimonial removed.');
    }
}
