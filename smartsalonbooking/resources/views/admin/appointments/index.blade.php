@extends('layouts.admin')

@section('title', 'Appointments')
@section('page_title', 'Appointments')

@section('content')
<form method="GET" class="filter-bar glass-card">
    <input type="text" name="search" class="form-control" placeholder="Search booking # or customer…" value="{{ request('search') }}">
    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    <select name="employee_id" class="form-control">
        <option value="">All Specialists</option>
        @foreach ($employees as $employee)
            <option value="{{ $employee->id }}" @selected(request('employee_id') == $employee->id)>{{ $employee->name }}</option>
        @endforeach
    </select>
    <select name="service_id" class="form-control">
        <option value="">All Services</option>
        @foreach ($services as $service)
            <option value="{{ $service->id }}" @selected(request('service_id') == $service->id)>{{ $service->name }}</option>
        @endforeach
    </select>
    <select name="status" class="form-control">
        <option value="">All Statuses</option>
        @foreach (['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn--primary btn--sm">Filter</button>
    <a href="{{ route('admin.appointments.index') }}" class="btn btn--ghost btn--sm">Clear</a>
</form>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Booking #</th><th>Customer</th><th>Service</th><th>Specialist</th><th>Date</th><th>Time</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->booking_number }}</td>
                    <td>{{ $appointment->customer?->user?->name ?? '—' }}</td>
                    <td>{{ $appointment->service->name }}</td>
                    <td>{{ $appointment->employee->name }}</td>
                    <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($appointment->start_time)->format('g:i A') }}</td>
                    <td><span class="badge badge--{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td>
                    <td><a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn--ghost btn--sm">Manage</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">No appointments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $appointments->links() }}
@endsection
