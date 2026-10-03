@extends('layouts.app')

@section('title', 'Book Premium Salon Services Online')

@section('content')

<section class="hero">
    <div class="container">
        <div class="hero__inner">
            <div class="fade-in">
                <span class="hero__eyebrow"><i class="fa-solid fa-wand-magic-sparkles"></i> Nairobi's boutique beauty studio</span>
                <h1>Look and feel your best, <span class="text-primary">beautifully booked.</span></h1>
                <p class="lead">Browse our services, pick your favourite specialist, and reserve your slot in under a minute - no phone calls, no waiting.</p>
                <div class="hero__actions">
                    <a href="{{ route('booking.create') }}" class="btn btn--primary btn--lg">Book Appointment</a>
                    <a href="{{ route('services.index') }}" class="btn btn--outline btn--lg">Explore Services</a>
                </div>
            </div>
            <div class="hero__image slide-up">
                <img src="https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=900&q=80" alt="Salon interior">
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__eyebrow">What we offer</span>
            <h2 class="section__title">Our Most Loved Services</h2>
            <p>Hand-picked treatments performed by specialists who genuinely love what they do.</p>
        </div>
        <div class="grid grid-3">
            @forelse ($featuredServices as $service)
                <div class="glass-card glass-card--hover service-card animate-on-scroll">
                    <img class="service-card__image" src="{{ $service->image ? asset('storage/'.$service->image) : 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=500&q=60' }}" alt="{{ $service->name }}">
                    <div class="service-card__body">
                        <span class="badge badge--active">{{ $service->category->name }}</span>
                        <h3 class="mt-1">{{ $service->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($service->description, 80) }}</p>
                        <div class="service-card__meta">
                            <span><i class="fa-regular fa-clock"></i> {{ $service->formattedDuration() }}</span>
                            <span class="service-card__price">${{ number_format($service->price) }}</span>
                        </div>
                        <a href="{{ route('booking.create') }}" class="btn btn--secondary btn--sm w-full mt-2">Book This</a>
                    </div>
                </div>
            @empty
                <p>Services coming soon - please check back shortly.</p>
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('services.index') }}" class="btn btn--outline">View All Services</a>
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="grid grid-3">
            <div class="glass-card text-center animate-on-scroll">
                <h3><i class="fa-solid fa-gem text-primary" style="display:block;margin-bottom:8px;font-size:1.8rem;"></i> Premium Products</h3>
                <p>We only use professional-grade products so results look great and last longer.</p>
            </div>
            <div class="glass-card text-center animate-on-scroll delay-1">
                <h3><i class="fa-solid fa-circle-check text-primary" style="display:block;margin-bottom:8px;font-size:1.8rem;"></i> Real-Time Booking</h3>
                <p>See live availability and pick the exact time that works for you - no back and forth.</p>
            </div>
            <div class="glass-card text-center animate-on-scroll delay-2">
                <h3><i class="fa-solid fa-user-check text-primary" style="display:block;margin-bottom:8px;font-size:1.8rem;"></i> Verified Specialists</h3>
                <p>Every stylist and therapist is vetted, trained, and genuinely great at their craft.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__eyebrow">Meet the team</span>
            <h2 class="section__title">Specialists You'll Love</h2>
        </div>
        <div class="grid grid-4">
            @foreach ($employees as $employee)
                <div class="glass-card employee-card animate-on-scroll">
                    <img class="employee-card__photo" src="{{ $employee->photo ? asset('storage/'.$employee->photo) : 'https://i.pravatar.cc/150?u='.$employee->id }}" alt="{{ $employee->name }}">
                    <h3 style="font-size:1.05rem;">{{ $employee->name }}</h3>
                    <div class="employee-card__role">{{ $employee->specialization }}</div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('team.index') }}" class="btn btn--outline">Meet the Whole Team</a>
        </div>
    </div>
</section>

@if ($testimonials->count())
<section class="section section--alt">
    <div class="container">
        <div class="section__header animate-on-scroll">
            <span class="section__eyebrow">Testimonials</span>
            <h2 class="section__title">What Our Clients Say</h2>
        </div>
        <div class="grid grid-3">
            @foreach ($testimonials as $t)
                <div class="glass-card testimonial-card animate-on-scroll">
                    <div class="testimonial-card__stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $t->rating ? 'fa-solid' : 'fa-regular' }} fa-star"></i>
                        @endfor
                    </div>
                    <p>&ldquo;{{ $t->message }}&rdquo;</p>
                    <div class="testimonial-card__author">
                        <img class="testimonial-card__avatar" src="{{ $t->customer_photo ? asset('storage/'.$t->customer_photo) : 'https://i.pravatar.cc/100?u=t'.$t->id }}" alt="{{ $t->customer_name }}">
                        <strong>{{ $t->customer_name }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section">
    <div class="container">
        <div class="glass-card text-center animate-on-scroll" style="padding:56px;">
            <h2>Ready to treat yourself?</h2>
            <p class="mb-3">Booking takes less than a minute - choose your service, pick a time, and you're done.</p>
            <a href="{{ route('booking.create') }}" class="btn btn--primary btn--lg">Book Your Appointment</a>
        </div>
    </div>
</section>

@endsection
