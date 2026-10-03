<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    protected function makeEmployeeAndService(): array
    {
        $category = Category::factory()->create();
        $service = Service::factory()->create([
            'category_id' => $category->id,
            'duration_minutes' => 30,
            'status' => 'active',
        ]);
        $employee = Employee::factory()->create([
            'working_days' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
            'working_hours_start' => '00:00:00',
            'working_hours_end' => '23:30:00',
            'status' => 'active',
        ]);
        $employee->services()->attach($service->id);

        return [$employee, $service];
    }

    public function test_a_logged_in_customer_can_book_an_available_slot(): void
    {
        [$employee, $service] = $this->makeEmployeeAndService();
        $user = User::factory()->customer()->create();
        Customer::factory()->create(['user_id' => $user->id]);

        $date = Carbon::tomorrow()->format('Y-m-d');

        $response = $this->actingAs($user)->post(route('booking.store'), [
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'appointment_date' => $date,
            'start_time' => '10:00',
        ]);

        $appointment = Appointment::first();

        $response->assertRedirect(route('booking.confirmation', $appointment));
        $this->assertNotNull($appointment);
        $this->assertSame($employee->id, $appointment->employee_id);
        $this->assertSame('10:00:00', $appointment->start_time);
        $this->assertStringStartsWith(config('app.booking_prefix'), $appointment->booking_number);
    }

    public function test_the_same_employee_slot_cannot_be_booked_twice(): void
    {
        [$employee, $service] = $this->makeEmployeeAndService();

        $firstUser = User::factory()->customer()->create();
        Customer::factory()->create(['user_id' => $firstUser->id]);

        $secondUser = User::factory()->customer()->create();
        Customer::factory()->create(['user_id' => $secondUser->id]);

        $date = Carbon::tomorrow()->format('Y-m-d');

        // First booking succeeds and claims the 10:00 slot.
        $this->actingAs($firstUser)->post(route('booking.store'), [
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'appointment_date' => $date,
            'start_time' => '10:00',
        ])->assertRedirect();

        $this->assertSame(1, Appointment::count());

        // Second customer tries to grab the exact same employee/date/time.
        $response = $this->actingAs($secondUser)->post(route('booking.store'), [
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'appointment_date' => $date,
            'start_time' => '10:00',
        ]);

        $response->assertSessionHasErrors('start_time');
        $this->assertSame(1, Appointment::count(), 'A second, conflicting appointment must not be created.');
    }

    public function test_available_slots_endpoint_excludes_an_already_booked_time(): void
    {
        [$employee, $service] = $this->makeEmployeeAndService();
        $user = User::factory()->customer()->create();
        Customer::factory()->create(['user_id' => $user->id]);
        $date = Carbon::tomorrow()->format('Y-m-d');

        $this->actingAs($user)->post(route('booking.store'), [
            'service_id' => $service->id,
            'employee_id' => $employee->id,
            'appointment_date' => $date,
            'start_time' => '10:00',
        ]);

        $response = $this->getJson(route('booking.slots', [
            'employee_id' => $employee->id,
            'service_id' => $service->id,
            'date' => $date,
        ]));

        $response->assertOk();
        $values = collect($response->json('slots'))->pluck('value');
        $this->assertNotContains('10:00', $values);
        $this->assertContains('10:30', $values);
    }
}
