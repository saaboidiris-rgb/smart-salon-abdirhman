<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['Hair', 'Nails', 'Makeup', 'Spa'];

        return [
            'title' => fake()->words(3, true),
            'image' => 'gallery/placeholder.jpg',
            'category' => fake()->randomElement($categories),
        ];
    }
}
