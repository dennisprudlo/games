<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPasswordReset extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'users_password_resets';

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Generates a unique password reset token.
     */
    public static function uniqueToken(): string
    {
        $token = null;
        do {
            $token = str()->random(128);
        } while (! isset($token) || self::where('token', $token)->exists());

        return $token;
    }
}
