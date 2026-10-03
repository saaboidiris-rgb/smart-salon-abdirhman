@extends('layouts.app')

@section('title', 'Our Services')

@section('content')
<section class="section" style="padding-bottom:20px;">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Full menu</span>
            <h2 class="section__title">Our Services</h2>
            <p>Every treatment we offer, with transparent pricing and durations.</p>
        </div>

        <form method="GET" action="{{ route('services.index') }}" class="filter-bar glass-card">
            <input type="text" name="search" class="form-control" placeholder="Search services…" value="{{ request('search') }}">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn--primary btn--sm">Filter</button>
            @if (request('search') || request('category'))
                <a href="{{ route('services.index') }}" class="btn btn--ghost btn--sm">Clear</a>
            @endif
        </form>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="grid grid-3">
            @forelse ($services as $service)
                <div class="glass-card glass-card--hover service-card">
                    <img class="service-card__image" src="{{ $service->image ? asset('storage/'.$service->image) : 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=500&q=60' }}" alt="{{ $service->name }}">
                    <div class="service-card__body">
                        <span class="badge badge--active">{{ $service->category->name }}</span>
                        <h3 class="mt-1">{{ $service->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($service->description, 90) }}</p>
                        <div class="service-card__meta">
                            <span><i class="fa-regular fa-clock"></i> {{ $service->formattedDuration() }}</span>
                            <span class="service-card__price">${{ number_format($service->price) }}</span>
                        </div>
                        <div class="flex gap-sm mt-2">
                            <a href="{{ route('services.show', $service) }}" class="btn btn--outline btn--sm w-full">Details</a>
                            <a href="{{ route('booking.create') }}" class="btn btn--primary btn--sm w-full">Book</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No services match your search. Try a different keyword or category.</p>
            @endforelse
        </div>

        {{ $services->links() }}
    </div>
</section>
@endsection
