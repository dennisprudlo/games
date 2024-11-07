<?php

namespace App\Http\Middleware;

use App\Enums\Locale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->guest()) {
            return $next($request);
        }

        $locale = auth()->user()->locale ?? Locale::german;
        $this->applyLocale($locale);

        return $next($request);
    }

    /**
     * Applies the locale for the application
     */
    public function applyLocale(Locale $locale): void
    {
        app()->setLocale($locale->value);
        setlocale(LC_TIME, $locale->value);
        setlocale(LC_MONETARY, $locale->value);
    }
}
