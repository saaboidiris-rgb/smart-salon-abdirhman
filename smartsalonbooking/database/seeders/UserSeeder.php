<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Creates the three demo logins shown on the login page:
     *   admin@smartsalon.test        / password
     *   receptionist@smartsalon.test / password
     *   customer@smartsalon.test     / password
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@smartsalon.test'],
            [
                'name' => 'Amara Wanjiru',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'phone' => '+254700000001',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'receptionist@smartsalon.test'],
            [
                'name' => 'Brian Otieno',
                'password' => Hash::make('password'),
                'role' => User::ROLE_RECEPTIONIST,
                'phone' => '+254700000002',
                'email_verified_at' => now(),
            ]
        );

        $demoCustomer = User::updateOrCreate(
            ['email' => 'customer@smartsalon.test'],
            [
                'name' => 'Faith Njeri',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CUSTOMER,
                'phone' => '+254700000003',
                'email_verified_at' => now(),
            ]
        );

        Customer::firstOrCreate(['user_id' => $demoCustomer->id], [
            'gender' => 'female',
            'address' => 'Kilimani, Nairobi',
        ]);
    }
}
