<?php

use App\Games\Wordle\Controller as WordleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\LocalizationRedirect;
use App\Http\Middleware\SetUserLocale;
use App\Http\Middleware\VerifyPasswordResetToken;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * The following routes should be localized.
 * These are usually public routes where there is no authenticated user
 * to get their preferred locale from. The browser locale will be used as a default
 * but can be manually overridden by altering the URL
 *
 * Authenticated routes do not need to be localized since the users preferred locale
 * will be used to select the locale
 */
Route::prefix(LaravelLocalization::setLocale())->middleware([LocalizationRedirect::class])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Product Routes
    |--------------------------------------------------------------------------
    |
    | These routes are publicly accessible and do not require authentication.
    |
    */
    Route::controller(ProductController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::get('/{game}', 'game')->name('game');
    });

    Route::prefix('sessions')->group(function () {
        Route::prefix('wordle')->controller(WordleController::class)->group(function () {
            Route::post('/', 'store')->name('sessions.wordle.store');
            Route::prefix('{wordle}')->group(function () {
                Route::get('/', 'show')->name('sessions.wordle.show');
                Route::patch('/', 'update')->name('sessions.wordle.update');
            });
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Public Authentication Routes
    |--------------------------------------------------------------------------
    |
    | The guest-middleware group redirects all requests to the dashboard if the user
    | trying to access the route is already authenticated.
    |
    | Use this group for pages that are only supposed to be accessible for guest
    | such as sign-in, forgot-password, etc.
    |
    */
    Route::middleware('guest')->group(function () {

        Route::prefix('signup')->controller(SignupController::class)->group(function () {
            Route::get('/', 'create')->name('signup.create');
            Route::post('/', 'store')->name('signup.store');
        });

        Route::prefix('signin')->controller(SessionController::class)->group(function () {
            Route::get('/', 'create')->name('signin.create');
            Route::post('/', 'store')->name('signin.store');

            Route::prefix('challenge')->controller(TwoFactorAuthenticationController::class)->group(function () {
                Route::get('/', 'create')->name('challenge.create');
                Route::post('/', 'store')->name('challenge.store');
            });
        });

        Route::prefix('forgot-password')->controller(ForgotPasswordController::class)->group(function () {
            Route::get('/', 'create')->name('password.forgot.create');
            Route::post('/', 'store')->name('password.forgot.store');
        });

        Route::prefix('reset-password')->controller(ResetPasswordController::class)->middleware(VerifyPasswordResetToken::class)->group(function () {
            Route::get('/{token}', 'create')->name('password.reset.create');
            Route::post('/{token}', 'store')->name('password.reset.store');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Private Auth Routes
|--------------------------------------------------------------------------
|
| The auth-middleware group redirects all requests to the sign-in page
| if the user trying to access the route is not authenticated.
|
| After successful authentication the user is being redirected to the
| intended destination
*/
Route::middleware(['auth', SetUserLocale::class])->group(function () {
    Route::post('/signout', [SessionController::class, 'destroy'])->name('signout');

    // Route::prefix('account')->controller(AccountController::class)->group(function () {
    //     Route::get('/', 'show')->name('account.show');
    //     Route::patch('/', 'update')->name('account.update');
    // });
});
