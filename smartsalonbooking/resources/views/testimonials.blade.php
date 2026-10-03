@extends('layouts.app')

@section('title', 'Testimonials')

@section('content')
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Kind words</span>
            <h2 class="section__title">What Our Clients Say</h2>
        </div>

        <div class="grid grid-3">
            @forelse ($testimonials as $t)
                <div class="glass-card testimonial-card animate-on-scroll">
                    <div class="testimonial-card__stars">{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</div>
                    <p>&ldquo;{{ $t->message }}&rdquo;</p>
                    <div class="testimonial-card__author">
                        <img class="testimonial-card__avatar" src="{{ $t->customer_photo ? asset('storage/'.$t->customer_photo) : 'https://i.pravatar.cc/100?u=t'.$t->id }}" alt="{{ $t->customer_name }}">
                        <strong>{{ $t->customer_name }}</strong>
                    </div>
                </div>
            @empty
                <p>No testimonials yet.</p>
            @endforelse
        </div>

        {{ $testimonials->links() }}
    </div>
</section>
@endsection
