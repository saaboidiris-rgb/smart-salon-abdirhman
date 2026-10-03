@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Our work</span>
            <h2 class="section__title">Gallery</h2>
            <p>A peek at the transformations we've helped create.</p>
        </div>

        @if ($categories->count())
            <div class="filter-bar flex-center">
                <a href="{{ route('gallery.index') }}" class="btn btn--sm {{ request('category') ? 'btn--ghost' : 'btn--primary' }}">All</a>
                @foreach ($categories as $cat)
                    <a href="{{ route('gallery.index', ['category' => $cat]) }}" class="btn btn--sm {{ request('category') === $cat ? 'btn--primary' : 'btn--ghost' }}">{{ $cat }}</a>
                @endforeach
            </div>
        @endif

        <div class="grid grid-4">
            @forelse ($images as $image)
                <div class="gallery-item animate-on-scroll">
                    <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->title ?? 'Gallery image' }}" loading="lazy">
                </div>
            @empty
                <p>No gallery images yet - check back soon!</p>
            @endforelse
        </div>

        {{ $images->links() }}
    </div>
</section>
@endsection
