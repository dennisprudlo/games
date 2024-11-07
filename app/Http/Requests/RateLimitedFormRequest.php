<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Cache\RateLimiting\Unlimited;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Context;

class RateLimitedFormRequest extends FormRequest
{
    /**
     * Gets the limit rules for the form request rate limiter.
     */
    public function limit(): Limit
    {
        return new Unlimited;
    }

    /**
     * Gets the rate limiter for the form request.
     */
    public function rateLimiter(Limit $limit): RateLimiter
    {
        return app(RateLimiter::class)->for(self::class, fn () => $limit);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    public function withValidator($validator): void
    {
        if ($validator->passes()) {
            $limit = $this->limit();
            $limit->key = 'api:'.$limit->key;
            $rateLimiter = $this->rateLimiter($limit);

            if (! ($limit instanceof Unlimited)) {
                Context::addHidden('rate-limit-headers', [
                    'X-RateLimit-Limit' => $limit->maxAttempts,
                    'X-RateLimit-Remaining' => $rateLimiter->remaining($limit->key, $limit->maxAttempts),
                ]);
            }

            //
            // Throw an exception if the form request was tried too many times
            if ($rateLimiter->tooManyAttempts($limit->key, $limit->maxAttempts)) {
                $secondsUntilReset = $rateLimiter->availableIn($limit->key);

                throw new ThrottleRequestsException('', null, [
                    'Retry-After' => $secondsUntilReset,
                    'X-RateLimit-Reset' => Carbon::now()->addRealSecond($secondsUntilReset)->getTimestamp(),
                ]);
            }

            //
            // If the validation passes hit the rate limiter
            $rateLimiter->hit($limit->key, $limit->decaySeconds);
        }
    }
}
