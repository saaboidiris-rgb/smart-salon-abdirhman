<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Service;
use App\Support\PlaceholderImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * 10 realistic services spread across the 5 categories from CategorySeeder.
     */
    public function run(): void
    {
        $services = [
            ['category' => 'Hair', 'name' => 'Classic Haircut & Style', 'duration' => 45, 'price' => 1500, 'desc' => 'A precision cut finished with a professional blow-dry style.'],
            ['category' => 'Hair', 'name' => 'Balayage Coloring', 'duration' => 150, 'price' => 8500, 'desc' => 'Hand-painted highlights for a natural, sun-kissed look.'],
            ['category' => 'Hair', 'name' => 'Keratin Smoothing Treatment', 'duration' => 120, 'price' => 6500, 'desc' => 'Reduces frizz and adds shine for up to 3 months.'],
            ['category' => 'Nails', 'name' => 'Gel Manicure', 'duration' => 45, 'price' => 1800, 'desc' => 'Long-lasting, chip-free color with a glossy finish.'],
            ['category' => 'Nails', 'name' => 'Luxury Spa Pedicure', 'duration' => 60, 'price' => 2200, 'desc' => 'Soak, scrub, massage and polish for tired feet.'],
            ['category' => 'Skin Care', 'name' => 'Hydrating Facial', 'duration' => 60, 'price' => 3500, 'desc' => 'Deep cleanse and hydration for a radiant glow.'],
            ['category' => 'Skin Care', 'name' => 'Anti-Aging Facial', 'duration' => 75, 'price' => 4800, 'desc' => 'Targets fine lines with active, skin-loving ingredients.'],
            ['category' => 'Massage', 'name' => 'Swedish Relaxation Massage', 'duration' => 60, 'price' => 3200, 'desc' => 'Full-body massage to ease tension and stress.'],
            ['category' => 'Massage', 'name' => 'Hot Stone Massage', 'duration' => 90, 'price' => 4500, 'desc' => 'Heated stones melt away deep muscle tension.'],
            ['category' => 'Makeup', 'name' => 'Bridal Makeup', 'duration' => 90, 'price' => 9000, 'desc' => 'Long-wear, camera-ready makeup for your big day.'],
        ];

        foreach ($services as $data) {
            $category = Category::where('name', $data['category'])->first();

            Service::firstOrCreate(
                ['name' => $data['name']],
                [
                    'category_id' => $category->id,
                    'slug' => Str::slug($data['name']),
                    'duration_minutes' => $data['duration'],
                    'price' => $data['price'],
                    'description' => $data['desc'],
                    'image' => PlaceholderImage::make('services', $data['name']),
                    'status' => 'active',
                ]
            );
        }
    }
}
