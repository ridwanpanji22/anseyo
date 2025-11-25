<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Retrieve a setting value with optional default fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $value = static::query()->where('key', $key)->value('value');
        } catch (QueryException $e) {
            if (self::isMissingTableException($e)) {
                return $default;
            }

            throw $e;
        }

        return $value ?? $default;
    }

    /**
     * Persist a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        try {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        } catch (QueryException $e) {
            if (self::isMissingTableException($e)) {
                return;
            }

            throw $e;
        }
    }

    /**
     * Determine whether the exception is caused by a missing settings table.
     */
    protected static function isMissingTableException(QueryException $e): bool
    {
        $message = Str::lower($e->getMessage());

        return Str::contains($message, [
            'no such table: settings',
            'base table or view not found: 1146',
            "relation 'settings' does not exist",
        ]);
    }
}
