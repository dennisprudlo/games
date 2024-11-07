<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\Authenticate;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class SessionController extends Controller
{
    /**
     * Show the sign in view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/SignIn');
    }

    /**
     * Attempts to sign in the user.
     */
    public function store(Authenticate $request): RedirectResponse
    {
        //
        // Error message for the response in case of a failed authentication attempt
        $failedAttempt = ['email' => trans('auth.signin.failed')];

        //
        // Validate the attempting user credentials
        /** @var \App\Models\User */
        $attemptingUser = User::firstWhere('email', $request->safe()->email);
        if (! isset($attemptingUser)) {
            return back()->withErrors($failedAttempt);
        }

        $passwordCorrect = Hash::check($request->safe()->password, $attemptingUser->password);

        $shouldRemember = $request->safe()->has('remember') && $request->safe()->remember === true;

        //
        // If the attempting user has two factor authentication enabled
        // we redirect the user to the challenge before authenticating
        if ($attemptingUser->twoFactorEnabled()) {
            $request->session()->put([
                'attempt.id' => $attemptingUser->uid,
                'attempt.password_correct' => $passwordCorrect,
                'attempt.remember' => $shouldRemember,
            ]);

            return redirect()->route('challenge.create');
        }

        if (! $passwordCorrect) {
            return back()->withErrors($failedAttempt);
        }

        //
        // Trying to authenticate the user with the given credentials
        // when two factor authentication is disabled
        $authenticationAttempt = Auth::attempt(
            $request->safe(['email', 'password']),
            $shouldRemember
        );

        //
        // Regenerate the session and redirect the user to its intended destination
        if ($authenticationAttempt) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        //
        // Redirect back when the authentication attempt failed
        return back()->withErrors($failedAttempt);
    }

    /**
     * Signs out the currently authenticated user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        //
        // Signing out the authenticated user
        Auth::logout();

        //
        // Invalidate session data
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        //
        // Redirect to the applications root
        return redirect()->route('signin.create');
    }
}
