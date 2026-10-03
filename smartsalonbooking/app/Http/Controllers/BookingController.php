<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Service;
use App\Models\User;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BookingController extends Controller
{
    public function __construct(protected AvailabilityService $availability) {}

    public function create(): View
    {
        return view('booking.create', [
            'categories' => Category::with(['services' => fn ($q) => $q->active()])->get(),
            'employees' => Employee::active()->with('services:id')->get(),
            'minDate' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * AJAX endpoint used by public/js/booking.js while the customer is
     * filling in the form - returns the open time slots as JSON.
     */
    public function availableSlots(Request $request): JsonResponse
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'service_id' => ['required', 'exists:services,id'],
            'date' => ['required', 'date'],
            'ignore_appointment_id' => ['nullable', 'integer', 'exists:appointments,id'],
        ]);

        $employee = Employee::findOrFail($request->integer('employee_id'));
        $service = Service::findOrFail($request->integer('service_id'));

        // Used by the "reschedule" screen: a customer's own current
        // appointment shouldn't block itself from showing as available.
        // Only honoured if the logged-in customer actually owns that booking.
        $ignoreId = null;
        if ($request->filled('ignore_appointment_id') && Auth::check() && Auth::user()->customer) {
            $candidate = Appointment::find($request->integer('ignore_appointment_id'));
            if ($candidate && $candidate->customer_id === Auth::user()->customer->id) {
                $ignoreId = $candidate->id;
            }
        }

        $slots = $this->availability->getAvailableSlots($employee, $service, $request->string('date'), $ignoreId);

        return response()->json(['slots' => $slots]);
    }

    public function store(BookingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $service = Service::findOrFail($data['service_id']);
        $employee = Employee::findOrFail($data['employee_id']);

        $startTime = $data['start_time'];
        $endTime = Carbon::parse($startTime)->addMinutes($service->duration_minutes)->format('H:i');

        // Final, authoritative availability check happens inside the
        // transaction, right before we insert - this is what actually stops
        // two people booking the same slot, not the UI.
        if (! $this->availability->isSlotStillFree($employee, $data['appointment_date'], $startTime, $endTime)) {
            return back()->withInput()->withErrors([
                'start_time' => 'Sorry, that time slot was just taken. Please choose another one.',
            ]);
        }

        $customer = $this->resolveCustomer($data);

        if ($customer === null) {
            return back()->withInput()->withErrors([
                'email' => 'Please log in as a customer (or log out) before booking.',
            ]);
        }

        try {
            $appointment = DB::transaction(function () use ($customer, $service, $employee, $data, $startTime, $endTime) {
                return Appointment::create([
                    'booking_number' => Appointment::generateBookingNumber(),
                    'customer_id' => $customer->id,
                    'service_id' => $service->id,
                    'employee_id' => $employee->id,
                    'appointment_date' => $data['appointment_date'],
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'price' => $service->price,
                    'status' => Appointment::STATUS_PENDING,
                    'notes' => $data['notes'] ?? null,
                ]);
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Extremely rare race: the unique index on
            // (employee_id, appointment_date, start_time) caught a
            // simultaneous booking that slipped past the check above.
            return back()->withInput()->withErrors([
                'start_time' => 'Sorry, that time slot was just taken. Please choose another one.',
            ]);
        }

        $appointment->payment()->create([
            'amount' => $service->price,
            'method' => 'cash',
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => $customer->user_id,
            'title' => 'Booking received',
            'message' => "Your booking {$appointment->booking_number} for {$service->name} on ".
                Carbon::parse($data['appointment_date'])->format('D, d M Y')." at {$startTime} is pending confirmation.",
            'type' => 'booking',
        ]);

        return redirect()->route('booking.confirmation', $appointment)
            ->with('success', 'Your appointment has been booked!');
    }

    public function confirmation(Appointment $appointment): View
    {
        $appointment->load(['service', 'employee', 'customer.user']);

        $user = Auth::user();
        $owns = $user && $user->customer && $user->customer->id === $appointment->customer_id;
        $isStaff = $user && $user->canAccessAdmin();

        abort_unless($owns || $isStaff, 403);

        return view('booking.confirmation', ['appointment' => $appointment]);
    }

    /**
     * Works out which Customer record this booking belongs to: the logged
     * in customer, or a brand new account created right here for a guest.
     * Returns null if a non-customer (admin/receptionist) is logged in.
     */
    protected function resolveCustomer(array $data): ?Customer
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (! $user->isCustomer()) {
                return null;
            }

            return $user->customer ?? Customer::create(['user_id' => $user->id]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_CUSTOMER,
        ]);

        $customer = Customer::create(['user_id' => $user->id]);

        Auth::login($user);

        return $customer;
    }
}
