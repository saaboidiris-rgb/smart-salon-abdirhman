<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Support\Carbon;

/**
 * All the "is this employee free?" logic lives here so BookingController
 * stays thin and the same rules can be reused anywhere else we need them
 * (e.g. the reschedule form in the customer dashboard).
 */
class AvailabilityService
{
    protected const DAY_MAP = [0 => 'sun', 1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat'];

    /**
     * Returns the open time slots for one employee, on one date, for a
     * service of a given duration. Each slot is spaced by the service's own
     * duration (a 45 minute service offers slots 45 minutes apart), and any
     * slot that would overlap an existing active appointment is skipped.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public function getAvailableSlots(Employee $employee, Service $service, string $date, ?int $ignoreAppointmentId = null): array
    {
        $day = Carbon::parse($date);

        if ($day->isPast() && ! $day->isToday()) {
            return [];
        }

        $dayAbbreviation = self::DAY_MAP[$day->dayOfWeek];
        if (! $employee->worksOn($dayAbbreviation)) {
            return [];
        }

        $duration = max((int) $service->duration_minutes, 5);

        $windowStart = Carbon::parse($date.' '.$employee->working_hours_start->format('H:i:s'));
        $windowEnd = Carbon::parse($date.' '.$employee->working_hours_end->format('H:i:s'));

        // Existing bookings for this employee on this date that still "hold"
        // their slot (pending/confirmed/rescheduled - not cancelled).
        $bookedRanges = Appointment::query()
            ->where('employee_id', $employee->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', Appointment::ACTIVE_STATUSES)
            ->when($ignoreAppointmentId, fn ($q) => $q->where('id', '!=', $ignoreAppointmentId))
            ->get(['start_time', 'end_time'])
            ->map(fn ($appointment) => [
                'start' => Carbon::parse($date.' '.$appointment->start_time),
                'end' => Carbon::parse($date.' '.$appointment->end_time),
            ]);

        $slots = [];
        $cursor = $windowStart->copy();
        $now = Carbon::now();

        while ($cursor->copy()->addMinutes($duration)->lte($windowEnd)) {
            $slotStart = $cursor->copy();
            $slotEnd = $cursor->copy()->addMinutes($duration);

            $isPastToday = $day->isToday() && $slotStart->lte($now);
            $overlaps = $bookedRanges->contains(
                fn ($range) => $slotStart->lt($range['end']) && $slotEnd->gt($range['start'])
            );

            if (! $isPastToday && ! $overlaps) {
                $slots[] = [
                    'value' => $slotStart->format('H:i'),
                    'label' => $slotStart->format('g:i A'),
                ];
            }

            $cursor->addMinutes($duration);
        }

        return $slots;
    }

    /**
     * Re-checks (server-side, right before saving) that a specific
     * start/end time is still free. Used as the final guard inside the
     * booking transaction so two people can't grab the same slot at once.
     */
    public function isSlotStillFree(Employee $employee, string $date, string $startTime, string $endTime, ?int $ignoreAppointmentId = null): bool
    {
        $slotStart = Carbon::parse($date.' '.$startTime);
        $slotEnd = Carbon::parse($date.' '.$endTime);

        $conflict = Appointment::query()
            ->where('employee_id', $employee->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', Appointment::ACTIVE_STATUSES)
            ->when($ignoreAppointmentId, fn ($q) => $q->where('id', '!=', $ignoreAppointmentId))
            ->get(['start_time', 'end_time'])
            ->contains(function ($appointment) use ($date, $slotStart, $slotEnd) {
                $existingStart = Carbon::parse($date.' '.$appointment->start_time);
                $existingEnd = Carbon::parse($date.' '.$appointment->end_time);

                return $slotStart->lt($existingEnd) && $slotEnd->gt($existingStart);
            });

        return ! $conflict;
    }
}
