<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hair', 'description' => 'Cuts, coloring and treatments for every hair type.'],
            ['name' => 'Nails', 'description' => 'Manicures and pedicures with premium products.'],
            ['name' => 'Skin Care', 'description' => 'Facials and treatments that leave your skin glowing.'],
            ['name' => 'Massage', 'description' => 'Relaxing and therapeutic massage treatments.'],
            ['name' => 'Makeup', 'description' => 'Everyday and special-occasion makeup application.'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
