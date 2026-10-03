<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Services\AvailabilityService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Creates 50 appointments spread across the last 10 days (as history)
     * and the next 14 days (as an upcoming schedule), reusing
     * AvailabilityService so every seeded booking is a genuinely open,
     * non-overlapping slot - the exact same rule real bookings follow.
     */
    public function run(): void
    {
        $availability = app(AvailabilityService::class);
        $customers = Customer::pluck('id');
        $employees = Employee::with('services')->get();

        if ($customers->isEmpty() || $employees->isEmpty()) {
            $this->command?->warn('Skipping AppointmentSeeder: run CustomerSeeder and EmployeeSeeder first.');

            return;
        }

        $created = 0;
        $attempts = 0;
        $maxAttempts = 800;

        while ($created < 50 && $attempts < $maxAttempts) {
            $attempts++;

            $employee = $employees->random();
            if ($employee->services->isEmpty()) {
                continue;
            }
            $service = $employee->services->random();

            $daysOffset = random_int(-10, 14);
            $date = Carbon::today()->addDays($daysOffset);

            $slots = $availability->getAvailableSlots($employee, $service, $date->format('Y-m-d'));
            if (empty($slots)) {
                continue;
            }

            $slot = $slots[array_rand($slots)];
            $endTime = Carbon::parse($slot['value'])->addMinutes($service->duration_minutes)->format('H:i');

            $status = $daysOffset < 0
                ? fake()->randomElement([Appointment::STATUS_COMPLETED, Appointment::STATUS_COMPLETED, Appointment::STATUS_CANCELLED])
                : fake()->randomElement([Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED, Appointment::STATUS_CONFIRMED]);

            $appointment = Appointment::create([
                'booking_number' => Appointment::generateBookingNumber(),
                'customer_id' => $customers->random(),
                'service_id' => $service->id,
                'employee_id' => $employee->id,
                'appointment_date' => $date->format('Y-m-d'),
                'start_time' => $slot['value'],
                'end_time' => $endTime,
                'price' => $service->price,
                'status' => $status,
            ]);

            $appointment->payment()->create([
                'amount' => $service->price,
                'method' => fake()->randomElement(['cash', 'card', 'mobile_money']),
                'status' => $status === Appointment::STATUS_COMPLETED ? 'paid' : 'pending',
                'paid_at' => $status === Appointment::STATUS_COMPLETED ? $date : null,
            ]);

            $created++;
        }

        $this->command?->info("AppointmentSeeder: created {$created} appointments.");
    }
}
