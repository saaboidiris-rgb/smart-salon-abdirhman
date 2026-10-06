<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_RESCHEDULED = 'rescheduled';

    /**
     * Statuses that still "hold" a time slot - i.e. count towards the
     * double-booking check. A cancelled appointment frees the slot back up.
     */
    public const ACTIVE_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_RESCHEDULED,
    ];

    protected $fillable = [
        'booking_number',
        'customer_id',
        'service_id',
        'employee_id',
        'appointment_date',
        'start_time',
        'end_time',
        'price',
        'status',
        'notes',
    ];

    /**
     * Route model binding (and route() URL generation) use booking_number
     * instead of the numeric id, so links look like
     * /booking/SBS-2026-0001/confirmation instead of /booking/5/confirmation.
     * This must match the ":booking_number" binding used in routes/web.php.
     */
    public function getRouteKeyName(): string
    {
        return 'booking_number';
    }

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'price' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', Carbon::today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', Carbon::today())
            ->whereIn('status', self::ACTIVE_STATUSES)
            ->orderBy('appointment_date')
            ->orderBy('start_time');
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function isUpcoming(): bool
    {
        $startsAt = Carbon::parse($this->appointment_date->format('Y-m-d').' '.$this->start_time);

        return $startsAt->isFuture() && in_array($this->status, self::ACTIVE_STATUSES, true);
    }

    public function canBeCancelled(): bool
    {
        return $this->isUpcoming() && $this->status !== self::STATUS_CANCELLED;
    }

    public function canBeRescheduled(): bool
    {
        return $this->canBeCancelled();
    }

    /**
     * Builds the human friendly booking number, e.g. SBS-2026-0001.
     * Wrapped in a transaction + lockForUpdate so two customers booking at
     * the exact same millisecond never end up with the same number.
     */
    public static function generateBookingNumber(): string
    {
        $prefix = config('app.booking_prefix', 'SBS');
        $year = now()->year;

        return DB::transaction(function () use ($prefix, $year) {
            $next = static::lastSequenceForYear($prefix, $year) + 1;

            return sprintf('%s-%d-%04d', $prefix, $year, $next);
        });
    }

    /**
     * Small helper kept separate so generateBookingNumber() stays readable.
     * Reads the highest booking number issued this year, locking that row so
     * concurrent bookings don't read a stale value. (Locking an aggregate like
     * COUNT(*) works on MySQL but PostgreSQL rejects it, and counting would
     * also reuse numbers after a booking is deleted.)
     */
    protected static function lastSequenceForYear(string $prefix, int $year): int
    {
        $last = static::where('booking_number', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('booking_number')
            ->lockForUpdate()
            ->value('booking_number');

        return $last ? (int) substr($last, strrpos($last, '-') + 1) : 0;
    }
}
