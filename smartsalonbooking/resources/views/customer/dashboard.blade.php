@extends('layouts.dashboard')

@section('title', 'My Dashboard')

@section('content')
<div class="dash-topbar">
    <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
    <a href="{{ route('booking.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Book New Service</a>
</div>

<div class="grid grid-3 mb-3">
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-calendar-check text-primary"></i></div>
        <div><div class="stat-card__value">{{ $totalBookings }}</div><div class="stat-card__label">Total Bookings</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-hourglass-half text-primary"></i></div>
        <div><div class="stat-card__value">{{ $upcomingCount }}</div><div class="stat-card__label">Upcoming</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-circle-check text-primary"></i></div>
        <div><div class="stat-card__value">{{ $completedCount }}</div><div class="stat-card__label">Completed</div></div>
    </div>
</div>

<div class="grid grid-2" style="align-items:flex-start;">
    <div class="glass-card">
        <h3>Upcoming Appointments</h3>
        @forelse ($upcoming as $appointment)
            <div class="flex-between" style="padding:12px 0; border-bottom:1px solid rgba(0,0,0,.06);">
                <div>
                    <strong>{{ $appointment->service->name }}</strong>
                    <div class="text-muted" style="font-size:.85rem;">
                        {{ $appointment->appointment_date->format('d M Y') }} at {{ \Illuminate\Support\Carbon::parse($appointment->start_time)->format('g:i A') }}
                        with {{ $appointment->employee->name }}
                    </div>
                </div>
                <span class="badge badge--{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
            </div>
        @empty
            <p class="text-muted">No upcoming appointments. <a href="{{ route('booking.create') }}">Book one now</a>.</p>
        @endforelse
        <a href="{{ route('customer.appointments.index') }}" class="btn btn--outline btn--sm mt-2">View All Appointments</a>
    </div>

    <div class="glass-card">
        <h3>Notifications</h3>
        @forelse ($notifications as $note)
            <div style="padding:12px 0; border-bottom:1px solid rgba(0,0,0,.06);">
                <strong>{{ $note->title }}</strong>
                <p class="mb-0" style="font-size:.85rem;">{{ $note->message }}</p>
                <span class="text-muted" style="font-size:.75rem;">{{ $note->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <p class="text-muted">No notifications yet.</p>
        @endforelse
    </div>
</div>
@endsection
