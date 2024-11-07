<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

class Challenge extends FormRequest
{
    /**
     * The user attempting the two factor challenge.
     */
    protected ?User $challengedUser = null;

    /**
     * Indicates if the user wishes to be remembered after login.
     */
    protected ?bool $remember = null;

    /**
     * Indicated if the password was correct when signing in
     */
    protected ?bool $passwordCorrect = null;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string'],
        ];
    }

    /**
     * Determine if the request has a valid two factor code.
     */
    public function hasValidCode(): bool
    {
        $secret = $this->challengedUser()->two_factor_secret;
        if (! isset($secret)) {
            return false;
        }

        return $this->code && (new Google2FA)->verifyKey(Crypt::decrypt($secret), $this->code);
    }

    /**
     * Get the valid recovery code if one exists on the request.
     */
    public function validRecoveryCode(): ?string
    {
        if (! $this->code) {
            return null;
        }

        return collect($this->challengedUser()->recoveryCodes())->first(function ($code) {
            return hash_equals($this->code, $code) ? $code : null;
        });
    }

    /**
     * Determine if there is a challenged user in the current session.
     */
    public function hasChallengedUser(): bool
    {
        return $this->session()->has('attempt.id') && User::uid($this->session()->get('attempt.id')) !== null;
    }

    /**
     * Get the user that is attempting the two factor challenge.
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function challengedUser(): User
    {
        //
        // Check if the challenged user has already been retrieved
        if (isset($this->challengedUser)) {
            return $this->challengedUser;
        }

        if ($this->session()->has('attempt.id') && $user = User::uid($this->session()->get('attempt.id'))) {

            //
            // Setting and returning the challenged user
            return $this->challengedUser = $user;
        }

        //
        // If the challenged user cannot be retrieved redirect back to the sign in view
        throw new HttpResponseException(
            redirect()->route('signin.create')
        );
    }

    /**
     * Determine if the user wanted to be remembered after login.
     */
    public function remember(): bool
    {
        if (! isset($this->remember)) {
            $this->remember = $this->session()->get('attempt.remember', false);
        }

        return $this->remember;
    }

    /**
     * Determine if the password was correct after signing in.
     */
    public function passwordCorrect(): bool
    {
        if (! isset($this->passwordCorrect)) {
            $this->passwordCorrect = $this->session()->get('attempt.password_correct', false);
        }

        return $this->passwordCorrect;
    }
}
