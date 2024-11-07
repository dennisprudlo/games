<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\RateLimitedFormRequest;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Validation\Rule;

class ForgotPassword extends RateLimitedFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function limit(): Limit
    {
        //
        // Any ip address may request a password reset 2 times every 5 minutes (every 2.5 minutes)
        return Limit::perMinutes(5, 2)->by(request()->ip() ?? '');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,\Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                Rule::exists(User::class),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.exists' => trans('auth.forgot-password.email.validations.exists'),
            'email.*' => trans('auth.forgot-password.email.validations.required'),
        ];
    }
}
