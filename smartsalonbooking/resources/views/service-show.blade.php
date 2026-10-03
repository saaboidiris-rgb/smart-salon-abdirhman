@extends('layouts.app')

@section('title', $service->name)

@section('content')
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="align-items:flex-start;">
            <img src="{{ $service->image ? asset('storage/'.$service->image) : 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=700&q=70' }}"
                 alt="{{ $service->name }}" style="border-radius:var(--radius-lg); box-shadow:var(--shadow-lg);">

            <div>
                <span class="badge badge--active">{{ $service->category->name }}</span>
                <h1 class="mt-1">{{ $service->name }}</h1>
                <p class="lead">{{ $service->description }}</p>

                <div class="glass-card">
                    <div class="flex-between mb-2">
                        <span class="text-muted">Duration</span>
                        <strong><i class="fa-regular fa-clock"></i> {{ $service->formattedDuration() }}</strong>
                    </div>
                    <div class="flex-between mb-3">
                        <span class="text-muted">Price</span>
                        <span class="service-card__price">${{ number_format($service->price) }}</span>
                    </div>
                    <a href="{{ route('booking.create') }}" class="btn btn--primary btn--block">Book This Service</a>
                </div>

                @if ($service->employees->count())
                    <h4 class="mt-3">Specialists who offer this</h4>
                    <div class="flex gap-sm" style="flex-wrap:wrap;">
                        @foreach ($service->employees as $employee)
                            <span class="badge badge--completed">{{ $employee->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        @if ($related->count())
            <div class="section__header mt-4">
                <h2 class="section__title">You Might Also Like</h2>
            </div>
            <div class="grid grid-3">
                @foreach ($related as $r)
                    <div class="glass-card glass-card--hover service-card">
                        <div class="service-card__body">
                            <h3>{{ $r->name }}</h3>
                            <div class="service-card__meta">
                                <span><i class="fa-regular fa-clock"></i> {{ $r->formattedDuration() }}</span>
                                <span class="service-card__price">${{ number_format($r->price) }}</span>
                            </div>
                            <a href="{{ route('services.show', $r) }}" class="btn btn--outline btn--sm w-full mt-2">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
