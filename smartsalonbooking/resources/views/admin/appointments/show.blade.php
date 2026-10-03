@extends('layouts.admin')

@section('title', 'Booking '.$appointment->booking_number)
@section('page_title', 'Booking '.$appointment->booking_number)

@section('content')
<div class="grid grid-2" style="align-items:flex-start;">
    <div class="glass-card booking-summary">
        <h3>Booking Details</h3>
        <dl>
            <dt>Booking #</dt><dd>{{ $appointment->booking_number }}</dd>
            <dt>Customer</dt><dd>{{ $appointment->customer?->user?->name ?? '—' }} ({{ $appointment->customer?->user?->email }})</dd>
            <dt>Service</dt><dd>{{ $appointment->service->name }}</dd>
            <dt>Specialist</dt><dd>{{ $appointment->employee->name }}</dd>
            <dt>Date</dt><dd>{{ $appointment->appointment_date->format('l, d M Y') }}</dd>
            <dt>Time</dt><dd>{{ \Illuminate\Support\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Illuminate\Support\Carbon::parse($appointment->end_time)->format('g:i A') }}</dd>
            <dt>Price</dt><dd>${{ number_format($appointment->price, 2) }}</dd>
            <dt>Payment</dt><dd><span class="badge badge--{{ $appointment->payment?->status === 'paid' ? 'active' : 'pending' }}">{{ ucfirst($appointment->payment?->status ?? 'n/a') }}</span></dd>
            <dt>Notes</dt><dd>{{ $appointment->notes ?: '—' }}</dd>
        </dl>
    </div>

    <div class="glass-card">
        <h3>Update Status</h3>
        <p class="text-muted">Current status: <span class="badge badge--{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span></p>
        <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
            @csrf @method('PATCH')
            <div class="form-group">
                <select name="status" class="form-control">
                    @foreach (['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'] as $status)
                        <option value="{{ $status }}" @selected($appointment->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn--primary w-full">Update Status</button>
        </form>

        <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}" class="mt-2"
              data-confirm-submit data-confirm-title="Delete this booking?" data-confirm-message="This permanently removes the booking record." data-confirm-label="Delete">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn--danger w-full">Delete Booking</button>
        </form>

        <a href="{{ route('admin.appointments.index') }}" class="btn btn--ghost w-full mt-2"><i class="fa-solid fa-arrow-left"></i> Back to Appointments</a>
    </div>
</div>
@endsection
