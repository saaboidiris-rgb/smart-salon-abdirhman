<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Service;
use App\Support\PlaceholderImage;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['name' => 'Grace Achieng', 'specialization' => 'Hair Stylist', 'categories' => ['Hair']],
            ['name' => 'Kevin Mwangi', 'specialization' => 'Barber & Colorist', 'categories' => ['Hair']],
            ['name' => 'Linda Chebet', 'specialization' => 'Nail Technician', 'categories' => ['Nails']],
            ['name' => 'Sarah Kamau', 'specialization' => 'Esthetician', 'categories' => ['Skin Care', 'Massage']],
            ['name' => 'Ivy Wambui', 'specialization' => 'Makeup Artist', 'categories' => ['Makeup', 'Skin Care']],
        ];

        foreach ($employees as $data) {
            $employee = Employee::firstOrCreate(
                ['name' => $data['name']],
                [
                    'email' => strtolower(str_replace(' ', '.', $data['name'])).'@smartsalon.test',
                    'phone' => '+2547'.random_int(10000000, 99999999),
                    'specialization' => $data['specialization'],
                    'bio' => "Passionate {$data['specialization']} with years of hands-on experience.",
                    'photo' => PlaceholderImage::make('employees', $data['name'], 400, 400),
                    'working_days' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
                    'working_hours_start' => '09:00:00',
                    'working_hours_end' => '18:00:00',
                    'status' => 'active',
                ]
            );

            $serviceIds = Service::whereHas('category', fn ($q) => $q->whereIn('name', $data['categories']))->pluck('id');
            $employee->services()->sync($serviceIds);
        }
    }
}
