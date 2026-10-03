<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Support\PlaceholderImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Balayage Result', 'category' => 'Hair'],
            ['title' => 'Bridal Updo', 'category' => 'Hair'],
            ['title' => 'Chrome Nail Art', 'category' => 'Nails'],
            ['title' => 'French Pedicure', 'category' => 'Nails'],
            ['title' => 'Glow Facial', 'category' => 'Skin Care'],
            ['title' => 'Bridal Glam', 'category' => 'Makeup'],
            ['title' => 'Editorial Makeup', 'category' => 'Makeup'],
            ['title' => 'Hot Stone Session', 'category' => 'Massage'],
            ['title' => 'Salon Interior', 'category' => 'Studio'],
            ['title' => 'Product Shelf', 'category' => 'Studio'],
            ['title' => 'Keratin Before & After', 'category' => 'Hair'],
            ['title' => 'Gel Manicure Set', 'category' => 'Nails'],
        ];

        foreach ($items as $item) {
            Gallery::firstOrCreate(
                ['title' => $item['title']],
                [
                    'image' => PlaceholderImage::make('gallery', $item['title'], 600, 600),
                    'category' => $item['category'],
                ]
            );
        }
    }
}
