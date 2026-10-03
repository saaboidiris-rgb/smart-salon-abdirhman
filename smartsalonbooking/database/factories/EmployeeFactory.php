<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+2547'.fake()->numberBetween(10000000, 99999999),
            'specialization' => fake()->randomElement([
                'Hair Stylist', 'Nail Technician', 'Makeup Artist', 'Massage Therapist', 'Skin Care Specialist',
            ]),
            'bio' => fake()->sentence(15),
            'working_days' => ['mon', 'tue', 'wed', 'thu', 'fri'],
            'working_hours_start' => '09:00:00',
            'working_hours_end' => '18:00:00',
            'status' => 'active',
        ];
    }
}
