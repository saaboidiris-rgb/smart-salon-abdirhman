<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'address' => '123 Blossom Avenue, Kilimani, Nairobi',
            'contact_phone' => '+254 700 000 001',
            'contact_email' => 'hello@smartsalon.test',
            'opening_hours' => 'Mon - Sat, 9:00 AM - 7:00 PM',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
