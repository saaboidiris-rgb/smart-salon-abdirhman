<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'photo',
        'bio',
        'working_days',
        'working_hours_start',
        'working_hours_end',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'working_days' => 'array',
            'working_hours_start' => 'datetime:H:i',
            'working_hours_end' => 'datetime:H:i',
        ];
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Does this employee work on the given day? $day is a lowercase
     * three-letter abbreviation: mon, tue, wed, thu, fri, sat, sun.
     */
    public function worksOn(string $day): bool
    {
        return in_array($day, $this->working_days ?? [], true);
    }
}
