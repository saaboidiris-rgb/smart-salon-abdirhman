<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * 30 demo customers (each with their own linked User login account).
     */
    public function run(): void
    {
        Customer::factory()->count(30)->create();
    }
}
