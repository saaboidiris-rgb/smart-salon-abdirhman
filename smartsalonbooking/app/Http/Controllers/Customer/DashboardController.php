<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $customer = Auth::user()->customer;

        $appointments = $customer
            ? Appointment::with(['service', 'employee'])
                ->where('customer_id', $customer->id)
                ->orderByDesc('appointment_date')
                ->orderByDesc('start_time')
                ->get()
            : collect();

        return view('customer.dashboard', [
            'upcoming' => $appointments->filter->isUpcoming()->take(5),
            'totalBookings' => $appointments->count(),
            'completedCount' => $appointments->where('status', Appointment::STATUS_COMPLETED)->count(),
            'upcomingCount' => $appointments->filter->isUpcoming()->count(),
            'notifications' => Auth::user()->notifications()->take(5)->get(),
        ]);
    }
}
