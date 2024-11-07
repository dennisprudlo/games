<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\RateLimitedFormRequest;
use Illuminate\Cache\RateLimiting\Limit;

class Authenticate extends RateLimitedFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function limit(): Limit
    {
        //
        // Any ip address may sign in 10 times per minute (every 6 seconds)
        return Limit::perMinute(10)->by(request()->ip() ?? '');
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
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'max:255',
            ],
            'remember' => [
                'nullable',
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
            'email.*' => trans('auth.signin.email.validations.required'),
            'password.*' => trans('auth.signin.password.validations.required'),
        ];
    }
}
