@extends('layouts.admin')

@section('title', $customer->user->name)
@section('page_title', 'Customer Profile')

@section('content')
<div class="grid grid-2" style="align-items:flex-start;">
    <div class="glass-card">
        <h3>{{ $customer->user->name }}</h3>
        <p class="mb-1">📧 {{ $customer->user->email }}</p>
        <p class="mb-1">📞 {{ $customer->user->phone }}</p>
        <p class="mb-1">⚧ {{ $customer->gender ? ucfirst($customer->gender) : 'Not set' }}</p>
        <p class="mb-1">📍 {{ $customer->address ?? 'Not set' }}</p>
        <p class="mb-0">🗓 Joined {{ $customer->created_at->format('d M Y') }}</p>
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn--outline btn--sm mt-2">Edit Details</a>
    </div>

    <div class="glass-card">
        <h3>Appointment History</h3>
        @forelse ($customer->appointments as $appointment)
            <div class="flex-between" style="padding:10px 0; border-bottom:1px solid rgba(0,0,0,.06);">
                <div>
                    <strong>{{ $appointment->service->name }}</strong>
                    <div class="text-muted" style="font-size:.82rem;">{{ $appointment->appointment_date->format('d M Y') }} with {{ $appointment->employee->name }}</div>
                </div>
                <span class="badge badge--{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
            </div>
        @empty
            <p class="text-muted">No appointments yet.</p>
        @endforelse
    </div>
</div>
@endsection
