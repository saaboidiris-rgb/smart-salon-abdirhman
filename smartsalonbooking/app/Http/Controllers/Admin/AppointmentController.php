<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $appointments = Appointment::with(['customer.user', 'service', 'employee'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('employee_id'), fn ($q) => $q->where('employee_id', $request->employee_id))
            ->when($request->filled('service_id'), fn ($q) => $q->where('service_id', $request->service_id))
            ->when($request->filled('date'), fn ($q) => $q->whereDate('appointment_date', $request->date))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($qq) use ($request) {
                    $qq->where('booking_number', 'like', '%'.$request->search.'%')
                        ->orWhereHas('customer.user', fn ($u) => $u->where('name', 'like', '%'.$request->search.'%'));
                });
            })
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(15)
            ->withQueryString();

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'employees' => Employee::orderBy('name')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load(['customer.user', 'service', 'employee', 'payment']);

        return view('admin.appointments.show', ['appointment' => $appointment]);
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,rescheduled'],
        ]);

        $appointment->update(['status' => $request->status]);

        if ($request->status === Appointment::STATUS_COMPLETED && $appointment->payment) {
            $appointment->payment->update(['status' => 'paid', 'paid_at' => now()]);
        }

        Notification::create([
            'user_id' => $appointment->customer->user_id,
            'title' => 'Booking status updated',
            'message' => "Your booking {$appointment->booking_number} is now {$request->status}.",
            'type' => 'booking',
        ]);

        return back()->with('success', 'Booking status updated.');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return back()->with('success', 'Booking deleted.');
    }
}
