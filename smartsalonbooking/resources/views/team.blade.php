@extends('layouts.app')

@section('title', 'Our Team')

@section('content')
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">The people behind the magic</span>
            <h2 class="section__title">Meet Our Team</h2>
            <p>Every specialist is trained, vetted, and passionate about their craft.</p>
        </div>

        <div class="grid grid-4">
            @foreach ($employees as $employee)
                <div class="glass-card glass-card--hover employee-card animate-on-scroll">
                    <img class="employee-card__photo" src="{{ $employee->photo ? asset('storage/'.$employee->photo) : 'https://i.pravatar.cc/150?u='.$employee->id }}" alt="{{ $employee->name }}">
                    <h3 style="font-size:1.05rem;">{{ $employee->name }}</h3>
                    <div class="employee-card__role">{{ $employee->specialization }}</div>
                    @if ($employee->services->count())
                        <p style="font-size:.82rem;">{{ $employee->services->pluck('name')->take(3)->implode(', ') }}</p>
                    @endif
                    <a href="{{ route('booking.create') }}" class="btn btn--outline btn--sm w-full mt-1">Book with {{ \Illuminate\Support\Str::before($employee->name, ' ') }}</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
