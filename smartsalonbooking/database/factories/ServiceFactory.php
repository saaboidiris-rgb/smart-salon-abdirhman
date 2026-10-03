<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Classic Haircut', 'Balayage Coloring', 'Deep Conditioning Treatment', 'Bridal Makeup',
            'Gel Manicure', 'Spa Pedicure', 'Swedish Massage', 'Hot Stone Massage', 'Facial Cleanse',
            'Eyebrow Threading', 'Eyelash Extensions', 'Keratin Treatment',
        ]);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
            'price' => fake()->randomElement([1200, 1800, 2500, 3000, 4500, 6000, 8000]),
            'description' => fake()->sentence(20),
            'status' => 'active',
        ];
    }
}
