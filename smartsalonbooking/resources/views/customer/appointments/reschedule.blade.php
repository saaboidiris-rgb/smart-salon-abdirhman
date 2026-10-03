@extends('layouts.dashboard')

@section('title', 'Reschedule Appointment')

@section('content')
<div class="dash-topbar">
    <h1>Reschedule Booking {{ $appointment->booking_number }}</h1>
</div>

<div class="glass-card" style="max-width:600px;">
    <p class="text-muted mb-3">Original: {{ $appointment->service->name }} with {{ $appointment->employee->name }} on {{ $appointment->appointment_date->format('d M Y') }} at {{ \Illuminate\Support\Carbon::parse($appointment->start_time)->format('g:i A') }}</p>

    <form method="POST" action="{{ route('customer.appointments.reschedule.update', $appointment) }}" id="reschedule-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label" for="employee_id">Specialist</label>
            <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected(old('employee_id', $appointment->employee_id) == $employee->id)>{{ $employee->name }}</option>
                @endforeach
            </select>
            @error('employee_id')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="appointment_date">New date</label>
            <input type="date" id="appointment_date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" min="{{ $minDate }}" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
            @error('appointment_date')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <label class="form-label">Available times</label>
        <div class="slot-grid" id="slot-grid"><p class="text-muted">Loading…</p></div>
        <input type="hidden" name="start_time" id="start_time_input" value="{{ old('start_time', $appointment->start_time) }}">
        @error('start_time')<span class="form-error">{{ $message }}</span>@enderror

        <div class="flex-between mt-3">
            <a href="{{ route('customer.appointments.index') }}" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary">Save New Time</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var employeeSelect = document.getElementById('employee_id');
    var dateInput = document.getElementById('appointment_date');
    var startTimeInput = document.getElementById('start_time_input');
    var grid = document.getElementById('slot-grid');
    var serviceId = {{ $appointment->service_id }};
    var slotsUrl = "{{ route('booking.slots') }}";

    function loadSlots() {
        var url = slotsUrl + '?employee_id=' + employeeSelect.value + '&service_id=' + serviceId + '&date=' + dateInput.value + '&ignore_appointment_id={{ $appointment->id }}';
        grid.innerHTML = '<p class="text-muted">Loading…</p>';
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var slots = data.slots || [];
                if (!slots.length) { grid.innerHTML = '<p class="form-error">No open slots that day.</p>'; return; }
                grid.innerHTML = '';
                slots.forEach(function (slot) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'slot' + (slot.value === startTimeInput.value ? ' is-selected' : '');
                    btn.textContent = slot.label;
                    btn.addEventListener('click', function () {
                        grid.querySelectorAll('.slot').forEach(function (s) { s.classList.remove('is-selected'); });
                        btn.classList.add('is-selected');
                        startTimeInput.value = slot.value;
                    });
                    grid.appendChild(btn);
                });
            });
    }

    employeeSelect.addEventListener('change', loadSlots);
    dateInput.addEventListener('change', loadSlots);
    loadSlots();
});
</script>
@endpush
