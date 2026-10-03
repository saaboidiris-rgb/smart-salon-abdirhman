<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', ['images' => Gallery::latest()->paginate(12)]);
    }

    public function create(): View
    {
        return view('admin.gallery.form', ['image' => new Gallery]);
    }

    public function store(GalleryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $request->file('image')->store('gallery', 'public');

        Gallery::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Image added to gallery.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.gallery.form', ['image' => $gallery]);
    }

    public function update(GalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($gallery->image);
            $data['image'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Image updated.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        Storage::disk('public')->delete($gallery->image);
        $gallery->delete();

        return back()->with('success', 'Image removed.');
    }
}
