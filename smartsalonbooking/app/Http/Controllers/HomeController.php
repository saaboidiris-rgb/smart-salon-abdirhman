<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'featuredServices' => Service::active()->with('category')->latest()->take(6)->get(),
            'employees' => Employee::active()->take(4)->get(),
            'testimonials' => Testimonial::active()->latest()->take(3)->get(),
        ]);
    }
}
