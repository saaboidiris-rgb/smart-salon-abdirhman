<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            // Human friendly booking number, e.g. SBS-2026-0001. See
            // App\Models\Appointment::generateBookingNumber().
            $table->string('booking_number')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->foreignId('employee_id')->constrained()->restrictOnDelete();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time');
            // Price snapshot at the time of booking, so later price changes
            // to the service don't rewrite history.
            $table->decimal('price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'])
                ->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // The core "no double booking" rule: one employee can only be in
            // one place at one time, so the same employee/date/start_time
            // combination can never be inserted twice.
            $table->unique(['employee_id', 'appointment_date', 'start_time']);
            $table->index(['appointment_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
