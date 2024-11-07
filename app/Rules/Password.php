<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class Password implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * The minimum amount of characters in a password.
     */
    public int $minCharacters = 8;

    /**
     * The maximum amount of characters in a password.
     */
    public int $maxCharacters = 200;

    /**
     * Whether mixed case is required.
     */
    public bool $mixedCase = false;

    /**
     * Whether digits are required.
     */
    public bool $digits = false;

    /**
     * Whether special characters are required.
     */
    public bool $specialCharacters = false;

    /**
     * Gets the default password rule behavior.
     */
    public static function default(): self
    {
        if (app()->environment('local')) {
            return new self;
        }

        return self::secure();
    }

    /**
     * Gets a secure password validator.
     */
    public static function secure(): self
    {
        $password = new self;
        $password->mixedCase = true;
        $password->digits = true;
        $password->specialCharacters = true;

        return $password;
    }

    /**
     * Gets a validation results summary for a password value.
     */
    public function results(string $value): object
    {
        return (object) [
            'min' => strlen($value) >= $this->minCharacters,
            'max' => strlen($value) <= $this->maxCharacters,
            'mixed' => ! $this->mixedCase || (preg_match('/[a-z]/', $value) !== 0 && preg_match('/[A-Z]/', $value) !== 0),
            'digits' => ! $this->digits || (preg_match('/[0-9]/', $value) !== 0),
            'special' => ! $this->specialCharacters || (preg_match('/[#?!@()$%^&*=_{}[\]:;"\'|\\<>,.\/~`±§+-]/', $value) !== 0),
        ];
    }

    /** {@inheritDoc} */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Validator::make(['attribute' => $value], ['attribute' => ['required', 'string']]);

        if ($validator->fails()) {
            $fail(trans('validation.password.string'));

            return;
        }

        $results = $this->results($value);

        if (! $results->min) {
            $fail(trans('validation.password.min', ['length' => $this->minCharacters]));
        }

        if (! $results->max) {
            $fail(trans('validation.password.max', ['length' => $this->maxCharacters]));
        }

        if (! $results->mixed) {
            $fail(trans('validation.password.mixed'));
        }

        if (! $results->digits) {
            $fail(trans('validation.password.digits'));
        }

        if (! $results->special) {
            $fail(trans('validation.password.special'));
        }
    }
}
