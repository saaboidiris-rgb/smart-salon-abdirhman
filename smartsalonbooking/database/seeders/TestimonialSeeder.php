<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Support\PlaceholderImage;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Cynthia M.', 'rating' => 5, 'message' => 'Booking took two minutes and my stylist was incredible. Will be back every month!'],
            ['name' => 'Daniel K.', 'rating' => 5, 'message' => 'Best haircut I have had in Nairobi. The online booking made it so easy to pick a time that worked.'],
            ['name' => 'Purity W.', 'rating' => 4, 'message' => 'Loved the hot stone massage. The glass-smooth booking experience is a big plus too.'],
            ['name' => 'Michael O.', 'rating' => 5, 'message' => 'The reminder notifications and easy rescheduling saved me when my schedule changed last minute.'],
            ['name' => 'Aisha N.', 'rating' => 5, 'message' => 'My bridal makeup was flawless and lasted the entire day. Highly recommend!'],
            ['name' => 'Peter L.', 'rating' => 4, 'message' => 'Clean studio, friendly staff, and the app tells you exactly which slots are open.'],
        ];

        foreach ($testimonials as $t) {
            Testimonial::firstOrCreate(
                ['customer_name' => $t['name']],
                [
                    'rating' => $t['rating'],
                    'message' => $t['message'],
                    'customer_photo' => PlaceholderImage::make('testimonials', $t['name'], 200, 200),
                    'status' => 'active',
                ]
            );
        }
    }
}
