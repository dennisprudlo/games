<?php

namespace App\Models;

use App\Concerns\TwoFactorAuthentication;
use App\Concerns\UniqueId;
use App\Contracts\ReferenceAware;
use App\Enums\Locale;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

/**
 * @property \App\Enums\Locale $locale
 * @property \Illuminate\Database\Eloquent\Collection<int,\App\Models\UserSession> $sessions
 */
class User extends Authenticatable implements HasLocalePreference, ReferenceAware
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthentication;
    use UniqueId;

    /**
     * {@inheritDoc}
     */
    protected $table = 'users';

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'password' => 'hashed',
        'locale' => Locale::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The relationship to the users sessions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\UserSession>
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Attributes
    |--------------------------------------------------------------------------
    */

    /**
     * Get the users avatar url.
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => isset($this->avatar) ? Storage::disk('public')->url($this->avatar) : null,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Checks whether the user has verified its email address
     */
    public function isVerified(): bool
    {
        return isset($this->email_verified_at);
    }

    /**
     * Sets a new pending email change address
     */
    public function setPendingEmailChange(string|null $email = null): void
    {
        //
        // Remove the change email request when a null email was passed
        if (! isset($email)) {
            UserEmailChange::query()->where('user_id', $this->id)->delete();

            return;
        }

        //
        // Tries to insert the first param array.
        // When there is an entry uniquely identifiable by the user_id (second array)
        // Update the email (third array)
        UserEmailChange::query()->upsert(
            ['user_id' => $this->getKey(), 'email' => $email],
            ['user_id'],
            ['email']
        );
    }

    /**
     * Gets the currently pending email
     */
    public function getPendingEmailChange(): string|null
    {
        return UserEmailChange::query()->firstWhere('user_id', $this->getKey())?->email;
    }

    /**
     * Sends the email with the verification link for the pending email change
     */
    public function sendPendingEmailVerification(): void
    {
        // TODO
        if ($pendingEmail = $this->getPendingEmailChange()) {
            // Mail::to($pendingEmail)->send(new VerifyEmail($this, $pendingEmail));
        } elseif (! $this->isVerified()) {
            // Mail::to($this)->send(new VerifyEmail($this, $this->email));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Implementations
    |--------------------------------------------------------------------------
    */

    /**
     * {@inheritDoc}
     */
    public function deleteWithRefs(): void
    {
        $this->sessions()->delete();
        $this->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function preferredLocale()
    {
        return $this->locale->value;
    }
}
