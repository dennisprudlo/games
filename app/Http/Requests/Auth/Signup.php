<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\RateLimitedFormRequest;
use App\Rules\Password;
use Illuminate\Cache\RateLimiting\Limit;

class Signup extends RateLimitedFormRequest
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
            'name' => [
                'required',
                'min:1',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
                'max:255',
            ],
            'password' => [
                'required',
                Password::default(),
            ],
            'accept' => [
                'accepted',
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
            'name.min' => trans('auth.signup.name.validations.min'),
            'name.max' => trans('auth.signup.name.validations.min'),
            'name.*' => trans('auth.signup.name.validations.required'),
            'email.unique' => trans('auth.signup.email.validations.unique'),
            'email.*' => trans('auth.signup.email.validations.required'),
            'password.*' => trans('auth.signup.password.validations.required'),
        ];
    }
}
