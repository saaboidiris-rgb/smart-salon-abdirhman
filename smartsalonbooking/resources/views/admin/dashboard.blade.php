@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

<div class="grid grid-4 mb-3">
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-users text-primary"></i></div>
        <div><div class="stat-card__value">{{ $stats['total_customers'] }}</div><div class="stat-card__label">Total Customers</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-calendar-day text-primary"></i></div>
        <div><div class="stat-card__value">{{ $stats['today_bookings'] }}</div><div class="stat-card__label">Today's Bookings</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-circle-check text-primary"></i></div>
        <div><div class="stat-card__value">{{ $stats['completed_services'] }}</div><div class="stat-card__label">Completed Services</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-dollar-sign text-primary"></i></div>
        <div><div class="stat-card__value">${{ number_format($stats['revenue']) }}</div><div class="stat-card__label">Revenue</div></div>
    </div>
</div>

<div class="grid grid-2 mb-3">
    <div class="glass-card chart-card">
        <h3>Revenue - Last 7 Days</h3>
        <canvas id="revenueChart" data-labels='{{ $chartLabels->toJson() }}' data-values='{{ $revenuePerDay->toJson() }}'></canvas>
    </div>
    <div class="glass-card chart-card">
        <h3>Bookings - Last 7 Days</h3>
        <canvas id="bookingsChart" data-labels='{{ $chartLabels->toJson() }}' data-values='{{ $bookingsPerDay->toJson() }}'></canvas>
    </div>
</div>

<div class="grid grid-2" style="align-items:flex-start;">
    <div class="glass-card">
        <h3>Recent Bookings</h3>
        <div class="table-wrapper" style="background:transparent;">
            <table class="table">
                <thead><tr><th>#</th><th>Customer</th><th>Service</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_number }}</td>
                            <td>{{ $booking->customer?->user?->name ?? '—' }}</td>
                            <td>{{ $booking->service->name }}</td>
                            <td>{{ $booking->appointment_date->format('d M') }}</td>
                            <td><span class="badge badge--{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">No bookings yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ route('admin.appointments.index') }}" class="btn btn--outline btn--sm mt-2">View All Bookings</a>
    </div>

    <div class="glass-card">
        <h3>Upcoming Schedule</h3>
        @forelse ($upcomingSchedule as $booking)
            <div class="flex-between" style="padding:10px 0; border-bottom:1px solid rgba(0,0,0,.06);">
                <div>
                    <strong>{{ $booking->service->name }}</strong>
                    <div class="text-muted" style="font-size:.82rem;">{{ $booking->customer?->user?->name }} · {{ $booking->employee->name }}</div>
                </div>
                <div class="text-muted" style="font-size:.82rem;">{{ $booking->appointment_date->format('d M') }}, {{ \Illuminate\Support\Carbon::parse($booking->start_time)->format('g:i A') }}</div>
            </div>
        @empty
            <p class="text-muted">Nothing scheduled yet.</p>
        @endforelse
    </div>
</div>

<div class="glass-card mt-3">
    <h3>Recent Customers</h3>
    <div class="table-wrapper" style="background:transparent;">
        <table class="table">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th></tr></thead>
            <tbody>
                @forelse ($recentCustomers as $customer)
                    <tr>
                        <td>{{ $customer->user->name }}</td>
                        <td>{{ $customer->user->email }}</td>
                        <td>{{ $customer->user->phone }}</td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No customers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
<script src="{{ asset('js/admin-charts.js') }}"></script>
@endpush
