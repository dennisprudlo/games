<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ForgotPassword;
use App\Models\User;
use App\Models\UserPasswordReset;
use App\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Requests an email to reset the users password
     */
    public function store(ForgotPassword $request): RedirectResponse
    {
        $email = $request->safe()->email;

        if ($user = User::firstWhere('email', $email)) {

            //
            // Generate a unique token
            $token = UserPasswordReset::uniqueToken();

            UserPasswordReset::upsert([
                'email' => $user->email,
                'token' => $token,
                'expires_at' => now()->addMinutes(config('auth.passwords.users.expire')),
            ], ['email']);

            Notification::add(trans('auth.forgot-password.email-sent'));

            // TODO
            // Mail::to($user)->send(new \App\Mail\ForgotPassword($user, $token));
        }

        return redirect()->route('signin.create');
    }
}
