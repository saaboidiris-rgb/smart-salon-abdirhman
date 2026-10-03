<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $stats = [
            'total_customers' => Customer::count(),
            'today_bookings' => Appointment::today()->count(),
            'completed_services' => Appointment::where('status', Appointment::STATUS_COMPLETED)->count(),
            'pending_appointments' => Appointment::where('status', Appointment::STATUS_PENDING)->count(),
            'revenue' => (float) Payment::where('status', 'paid')->sum('amount'),
        ];

        $recentBookings = Appointment::with(['customer.user', 'service', 'employee'])
            ->latest()
            ->take(8)
            ->get();

        $upcomingSchedule = Appointment::with(['customer.user', 'service', 'employee'])
            ->upcoming()
            ->take(8)
            ->get();

        $recentCustomers = Customer::with('user')->latest()->take(5)->get();

        // Last 7 days of bookings + revenue, for the two dashboard charts.
        $days = collect(range(6, 0))->map(fn ($i) => $today->copy()->subDays($i));

        $bookingsPerDay = $days->map(function (Carbon $day) {
            return Appointment::whereDate('appointment_date', $day)->count();
        });

        $revenuePerDay = $days->map(function (Carbon $day) {
            return (float) Payment::where('status', 'paid')->whereDate('paid_at', $day)->sum('amount');
        });

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
            'upcomingSchedule' => $upcomingSchedule,
            'recentCustomers' => $recentCustomers,
            'chartLabels' => $days->map(fn (Carbon $d) => $d->format('D')),
            'bookingsPerDay' => $bookingsPerDay,
            'revenuePerDay' => $revenuePerDay,
        ]);
    }
}
