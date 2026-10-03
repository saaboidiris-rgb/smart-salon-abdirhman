<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /** @use HasFactory<\Database\Factories\TestimonialFactory> */
    use HasFactory;

    protected $fillable = ['customer_name', 'customer_photo', 'rating', 'message', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
