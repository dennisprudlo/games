<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Locale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Signup;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SignupController extends Controller
{
    /**
     * Shows the sign up view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/SignUp', [
            'email' => $request->route('email'),
        ]);
    }

    /**
     * Attempts to sign up an user.
     */
    public function store(Signup $request): RedirectResponse
    {
        /** @var \App\Models\User */
        $user = User::query()->create([
            'name' => $request->safe()->name,
            'email' => $request->safe()->email,
            'email_verified_at' => null,
            'password' => $request->safe()->password,
            'locale' => Locale::supported(app()->getLocale()) ? Locale::from(app()->getLocale()) : Locale::english,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $user->update([
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ], ['timestamps' => false]);

        $user->sendPendingEmailVerification();

        //
        // Signing in the user and regenerate its session
        Auth::login($user);
        $request->session()->regenerate();

        //
        // Redirect to the applications dashboard
        return redirect()->route('dashboard');
    }
}
