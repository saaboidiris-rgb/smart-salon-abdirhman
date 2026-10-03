<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Order matters: categories before services, employees before
     * appointments (which also need customers and services to already
     * exist). Run with: php artisan migrate --seed
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            EmployeeSeeder::class,
            CustomerSeeder::class,
            AppointmentSeeder::class,
            GallerySeeder::class,
            TestimonialSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
