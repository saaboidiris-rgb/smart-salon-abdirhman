<?php

namespace App\Models;

// Illuminate\Foundation\Auth\User already gives us Authenticatable behaviour
// (password hashing helpers, "remember me" token handling, etc.) so we build
// on top of it instead of the plain Eloquent Model class.
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The three roles this app understands. Kept here as constants so
     * controllers/blade views can do User::ROLE_ADMIN instead of typing the
     * raw string and risking a typo.
     */
    public const ROLE_ADMIN = 'admin';

    public const ROLE_RECEPTIONIST = 'receptionist';

    public const ROLE_CUSTOMER = 'customer';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Extra salon profile fields (gender, address, ...) when this user is a
     * customer. See database/migrations/..._create_customers_table.php.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isReceptionist(): bool
    {
        return $this->role === self::ROLE_RECEPTIONIST;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    /**
     * Admins and receptionists both use the /admin area, just with slightly
     * different permissions inside it.
     */
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin() || $this->isReceptionist();
    }
}
