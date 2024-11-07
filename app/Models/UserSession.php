<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \App\Models\User $user
 */
class UserSession extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'users_sessions';

    /**
     * {@inheritDoc}
     */
    public $incrementing = false;

    /**
     * {@inheritDoc}
     */
    protected $keyType = 'string';

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'last_activity' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The relationship to the sessions user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\User,\App\Models\UserSession>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Invalidates the session.
     */
    public function invalidate(): void
    {
        $this->forceDelete();
    }

    /**
     * Gets the deserialized session payload.
     */
    public function payload(?string $path = null): mixed
    {
        try {
            $payload = unserialize(base64_decode($this->payload));

            if (isset($path)) {
                return data_get($payload, $path);
            }

            return $payload;
        } catch (\Throwable) {
            return null;
        }
    }
}
