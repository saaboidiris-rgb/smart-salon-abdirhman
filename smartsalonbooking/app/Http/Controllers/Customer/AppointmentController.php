<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\RescheduleRequest;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Notification;
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AppointmentController extends Controller
{
    public function __construct(protected AvailabilityService $availability) {}

    public function index(Request $request): View
    {
        $customer = Auth::user()->customer;

        $appointments = Appointment::with(['service', 'employee'])
            ->where('customer_id', $customer?->id)
            ->status($request->get('status'))
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(10)
            ->withQueryString();

        return view('customer.appointments.index', ['appointments' => $appointments]);
    }

    public function editReschedule(Appointment $appointment): View
    {
        $this->authorizeOwnership($appointment);
        abort_unless($appointment->canBeRescheduled(), 403, 'This booking can no longer be rescheduled.');

        return view('customer.appointments.reschedule', [
            'appointment' => $appointment,
            'employees' => Employee::active()->whereHas('services', fn ($q) => $q->where('services.id', $appointment->service_id))->get(),
            'minDate' => now()->format('Y-m-d'),
        ]);
    }

    public function updateReschedule(RescheduleRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->authorizeOwnership($appointment);
        abort_unless($appointment->canBeRescheduled(), 403, 'This booking can no longer be rescheduled.');

        $data = $request->validated();
        $service = $appointment->service;
        $employee = Employee::findOrFail($data['employee_id']);
        $endTime = Carbon::parse($data['start_time'])->addMinutes($service->duration_minutes)->format('H:i');

        if (! $this->availability->isSlotStillFree($employee, $data['appointment_date'], $data['start_time'], $endTime, $appointment->id)) {
            return back()->withInput()->withErrors(['start_time' => 'That time is no longer available. Please pick another.']);
        }

        $appointment->update([
            'employee_id' => $employee->id,
            'appointment_date' => $data['appointment_date'],
            'start_time' => $data['start_time'],
            'end_time' => $endTime,
            'status' => Appointment::STATUS_RESCHEDULED,
        ]);

        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Booking rescheduled',
            'message' => "Your booking {$appointment->booking_number} was moved to ".
                Carbon::parse($data['appointment_date'])->format('D, d M Y')." at {$data['start_time']}.",
            'type' => 'booking',
        ]);

        return redirect()->route('customer.appointments.index')->with('success', 'Your appointment has been rescheduled.');
    }

    public function cancel(Appointment $appointment): RedirectResponse
    {
        $this->authorizeOwnership($appointment);
        abort_unless($appointment->canBeCancelled(), 403, 'This booking can no longer be cancelled.');

        $appointment->update(['status' => Appointment::STATUS_CANCELLED]);

        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Booking cancelled',
            'message' => "Your booking {$appointment->booking_number} has been cancelled.",
            'type' => 'booking',
        ]);

        return back()->with('success', 'Your appointment has been cancelled.');
    }

    protected function authorizeOwnership(Appointment $appointment): void
    {
        abort_unless(Auth::user()->customer && $appointment->customer_id === Auth::user()->customer->id, 403);
    }
}
