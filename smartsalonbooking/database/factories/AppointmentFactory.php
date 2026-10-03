<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 *
 * NOTE: because appointments have a unique (employee_id, appointment_date,
 * start_time) constraint, DatabaseSeeder does NOT call Appointment::factory()
 * in a tight loop with random times (that would eventually collide). Instead
 * database/seeders/AppointmentSeeder.php uses App\Services\AvailabilityService
 * to pick real open slots. This factory is here for completeness / for your
 * own tests, where you'll usually override employee_id/appointment_date/
 * start_time explicitly.
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $service = Service::inRandomOrder()->first() ?? Service::factory()->create();
        $date = fake()->dateTimeBetween('now', '+14 days')->format('Y-m-d');
        $start = fake()->randomElement(['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00']);

        return [
            'booking_number' => Appointment::generateBookingNumber(),
            'customer_id' => Customer::inRandomOrder()->first()?->id ?? Customer::factory(),
            'service_id' => $service->id,
            'employee_id' => Employee::inRandomOrder()->first()?->id ?? Employee::factory(),
            'appointment_date' => $date,
            'start_time' => $start,
            'end_time' => date('H:i', strtotime($start.' +'.$service->duration_minutes.' minutes')),
            'price' => $service->price,
            'status' => Appointment::STATUS_PENDING,
        ];
    }
}
