@extends('layouts.admin')

@section('title', 'Gallery')
@section('page_title', 'Gallery')

@section('content')
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Photos shown on the public gallery page.</p>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn--primary">➕ Add Image</a>
</div>

<div class="grid grid-4">
    @forelse ($images as $image)
        <div class="glass-card" style="padding:12px;">
            <div class="gallery-item mb-2"><img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->title }}"></div>
            <strong style="font-size:.85rem;">{{ $image->title ?? 'Untitled' }}</strong>
            @if ($image->category)<div class="text-muted" style="font-size:.78rem;">{{ $image->category }}</div>@endif
            <div class="row-actions mt-2">
                <a href="{{ route('admin.gallery.edit', $image) }}" class="btn btn--ghost btn--sm w-full">Edit</a>
                <form method="POST" action="{{ route('admin.gallery.destroy', $image) }}" class="w-full"
                      data-confirm-submit data-confirm-title="Delete this image?" data-confirm-label="Delete">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn--danger btn--sm w-full">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-muted">No gallery images yet.</p>
    @endforelse
</div>
{{ $images->links() }}
@endsection
