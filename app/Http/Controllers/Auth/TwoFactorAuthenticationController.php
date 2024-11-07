<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\Challenge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * Show the two factor authentication challenge view.
     */
    public function create(Challenge $request): Response
    {
        //
        // Check if the current session has a user to challenge
        // If not redirect the user back to the login screen
        if (! $request->hasChallengedUser()) {
            return redirect()->route('signin.create');
        }

        return Inertia::render('Auth/Challenge');
    }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     */
    public function store(Challenge $request): RedirectResponse
    {
        //
        // Retrieve the challenged user
        $user = $request->challengedUser();

        if (! $request->passwordCorrect()) {
            return back()->withErrors([
                'code' => trans('auth.challenge.code.validations.invalid-credentials'),
            ]);
        }

        //
        // First check if the user tries to authenticate using a recovery code
        // If so, the code must be valid and will be replaced afterwards
        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);
        } elseif (! $request->hasValidCode()) {

            //
            // If there was no recovery code set and the given
            // two factor authentication code was invalid, abort
            return back()->withErrors([
                'code' => trans('auth.challenge.code.validations.invalid-credentials'),
            ]);
        }

        //
        // Sign in the challenged user, regenerate the session
        Auth::login($user, $request->remember());
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
