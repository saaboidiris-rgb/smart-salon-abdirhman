<?php

use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\AppointmentController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/services', [PageController::class, 'services'])->name('services.index');
Route::get('/services/{service:slug}', [PageController::class, 'serviceShow'])->name('services.show');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery.index');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing.index');
Route::get('/team', [PageController::class, 'team'])->name('team.index');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials.index');
Route::get('/contact', [PageController::class, 'contact'])->name('contact.index');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Booking (public - guests can book, but must supply their details)
|--------------------------------------------------------------------------
*/
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/slots', [BookingController::class, 'availableSlots'])->name('booking.slots');
Route::get('/booking/{appointment:booking_number}/confirmation', [BookingController::class, 'confirmation'])
    ->name('booking.confirmation');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Customer dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->prefix('dashboard')->name('customer.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}/reschedule', [AppointmentController::class, 'editReschedule'])
        ->name('appointments.reschedule');
    Route::put('/appointments/{appointment}/reschedule', [AppointmentController::class, 'updateReschedule'])
        ->name('appointments.reschedule.update');
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin / receptionist area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,receptionist'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('services', AdminServiceController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('employees', EmployeeController::class)->except(['show']);
    Route::resource('gallery', AdminGalleryController::class)->except(['show']);
    Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);

    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [AdminCustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [AdminCustomerController::class, 'update'])->name('customers.update');

    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])
        ->name('appointments.status');
    Route::delete('/appointments/{appointment}', [AdminAppointmentController::class, 'destroy'])
        ->name('appointments.destroy');
});
