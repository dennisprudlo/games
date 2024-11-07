<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-base-url="{{ config('app.url') }}" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="copyright" content="Copyright &copy; {{ now()->year }} Games">
        <meta name="language" content="{{ app()->getLocale() }}">
        <meta name="locale" content="{{ app()->getLocale() }}">
        <meta name="robots" content="index, follow">

        <meta name="theme-color" content="#18181b" />
        <meta name="theme-color" content="#18181b" media="(prefers-color-scheme: light)">
        <meta name="theme-color" content="#18181b" media="(prefers-color-scheme: dark)">

        {{-- Open Graph Properties --}}
        <meta property="og:locale" content="{{ app()->getLocale() }}" />
        <meta property="og:url" content="{{ url()->current() }}" />

        {{-- Include the site webmanifest --}}
        <link rel="manifest" href="{{ asset('site-'.app()->getLocale().'.webmanifest') }}" />
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icon/192.png') }}" />
        <link rel="apple-touch-icon" sizes="196x196" href="{{ asset('images/icon/192.png') }}" />
        <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('images/icon/512.png') }}" />

        <meta name="mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-title" content="Games" />
        <meta name="application-name" content="Games" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />

        @routes
        <script src="{{ asset('build/lang.js') }}"></script>
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>
