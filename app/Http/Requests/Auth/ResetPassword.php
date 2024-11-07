<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\RateLimitedFormRequest;
use App\Rules\Password;
use Illuminate\Cache\RateLimiting\Limit;

class ResetPassword extends RateLimitedFormRequest
{
    /**
     * {@inheritDoc}
     */
    public function limit(): Limit
    {
        return Limit::perMinute(2)->by(request()->ip() ?? '');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
            ],
            'password' => [
                Password::default(),
            ],
        ];
    }

    /**
     * Get the validation messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            //
        ];
    }
}
