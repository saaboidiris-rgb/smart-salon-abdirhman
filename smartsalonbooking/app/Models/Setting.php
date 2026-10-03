<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Read a setting by key with a fallback, e.g. Setting::get('contact_phone').
     * Cached for a minute so we're not hitting the database on every page
     * load just to render the footer.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $settings = Cache::remember('settings.all', 60, function () {
            return static::query()->pluck('value', 'key');
        });

        return $settings[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings.all');
    }
}
