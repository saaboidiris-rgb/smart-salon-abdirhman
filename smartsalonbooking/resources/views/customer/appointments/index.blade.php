@extends('layouts.dashboard')

@section('title', 'My Appointments')

@section('content')
<div class="dash-topbar">
    <h1>My Appointments</h1>
    <a href="{{ route('booking.create') }}" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Book New Service</a>
</div>

<form method="GET" class="filter-bar glass-card">
    <select name="status" class="form-control" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        @foreach (['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'] as $status)
            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
        @endforeach
    </select>
</form>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead>
            <tr>
                <th>Booking #</th><th>Service</th><th>Specialist</th><th>Date</th><th>Time</th><th>Status</th><th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->booking_number }}</td>
                    <td>{{ $appointment->service->name }}</td>
                    <td>{{ $appointment->employee->name }}</td>
                    <td>{{ $appointment->appointment_date->format('d M Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($appointment->start_time)->format('g:i A') }}</td>
                    <td><span class="badge badge--{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></td>
                    <td>
                        <div class="row-actions">
                            @if ($appointment->canBeRescheduled())
                                <a href="{{ route('customer.appointments.reschedule', $appointment) }}" class="btn btn--ghost btn--sm">Reschedule</a>
                            @endif
                            @if ($appointment->canBeCancelled())
                                <form method="POST" action="{{ route('customer.appointments.cancel', $appointment) }}"
                                      data-confirm-submit
                                      data-confirm-title="Cancel this booking?"
                                      data-confirm-message="Your slot will be released and this can't be undone."
                                      data-confirm-label="Yes, cancel">
                                    @csrf
                                    <button type="submit" class="btn btn--danger btn--sm">Cancel</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">No appointments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $appointments->links() }}
@endsection
