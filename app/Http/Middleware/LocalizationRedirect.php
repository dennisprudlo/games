<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LanguageNegotiator;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationMiddlewareBase;
use Symfony\Component\HttpFoundation\Response;

class LocalizationRedirect extends LaravelLocalizationMiddlewareBase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //
        // Skip this redirect request when the url should be ignored.
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }

        $params = explode('/', $request->path());

        /** @var \Mcamara\LaravelLocalization\LaravelLocalization */
        $localization = app('laravellocalization');

        //
        // If the url contains at least one segment and that segment is a valid and supported locale, continue with the request
        // there is no need to redirect the user hence he already requested a localized url
        if ($localization->checkLocaleInSupportedLocales($params[0])) {
            return $next($request);
        }

        //
        // In any other case (no locale specified) negotiate the user's locale
        // Create the negotiator that considers the language header
        $negotiator = new LanguageNegotiator(
            config('app.locale'),
            $localization->getSupportedLocales(),
            request()
        );

        //
        // Negotiate language using the Accept-Language header
        $locale = $negotiator->negotiateLanguage();

        //
        // If the determined locale is not supported continue the request.
        // Eventually a 404 error page will be raised.
        if (! $locale || ! $localization->checkLocaleInSupportedLocales($locale)) {
            return $next($request);
        }

        //
        // Redirect to the negotiated locale
        $redirection = (string) $localization->getLocalizedURL($locale, request()->fullUrl());

        return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
    }
}
