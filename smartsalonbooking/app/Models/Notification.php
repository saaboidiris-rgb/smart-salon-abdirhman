<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Our own simple notifications model - NOT Laravel's built-in notifications
 * system. Rows are plain database records so beginners can query them with
 * normal Eloquent (Notification::where(...)) instead of learning the
 * polymorphic "notifiable" system.
 */
class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'type', 'is_read'];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
