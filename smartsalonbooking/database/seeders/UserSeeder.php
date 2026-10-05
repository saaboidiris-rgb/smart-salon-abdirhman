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
     *   Sabirin / Sab@1234
     *   Suhaylo / Suh@123
     *   Hawo    / Haw@1234
     */
    public function run(): void
    {
        $demoAccounts = [
            'admin@smartsalon.test' => [
                'name' => 'Admin',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
                'phone' => '+254700000001',
            ],
            'receptionist@smartsalon.test' => [
                'name' => 'Receptionist',
                'password' => 'password',
                'role' => User::ROLE_RECEPTIONIST,
                'phone' => '+254700000002',
            ],
            'customer@smartsalon.test' => [
                'name' => 'Customer',
                'password' => 'password',
                'role' => User::ROLE_CUSTOMER,
                'phone' => '+254700000003',
                'customer_gender' => 'female',
                'customer_address' => 'Kilimani, Nairobi',
            ],
        ];

        foreach ($demoAccounts as $email => $details) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $details['name'],
                    'password' => Hash::make($details['password']),
                    'role' => $details['role'],
                    'phone' => $details['phone'],
                    'email_verified_at' => now(),
                ]
            );

            if ($details['role'] === User::ROLE_CUSTOMER) {
                Customer::firstOrCreate(['user_id' => $user->id], [
                    'gender' => $details['customer_gender'] ?? 'female',
                    'address' => $details['customer_address'] ?? 'Nairobi',
                ]);
            }
        }

        $legacyAccounts = [
            'sabirin@smartsalon.test' => ['name' => 'Sabirin', 'role' => User::ROLE_ADMIN, 'password' => 'Sab@1234', 'phone' => '+254700000004'],
            'suhaylo@smartsalon.test' => ['name' => 'Suhaylo', 'role' => User::ROLE_RECEPTIONIST, 'password' => 'Suh@123', 'phone' => '+254700000005'],
            'hawo@smartsalon.test' => ['name' => 'Hawo', 'role' => User::ROLE_CUSTOMER, 'password' => 'Haw@1234', 'phone' => '+254700000006'],
        ];

        foreach ($legacyAccounts as $email => $details) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $details['name'],
                    'password' => Hash::make($details['password']),
                    'role' => $details['role'],
                    'phone' => $details['phone'],
                    'email_verified_at' => now(),
                ]
            );

            if ($details['role'] === User::ROLE_CUSTOMER) {
                Customer::firstOrCreate(['user_id' => $user->id], [
                    'gender' => 'female',
                    'address' => 'Kilimani, Nairobi',
                ]);
            }
        }
    }
}
