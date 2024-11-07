<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\ResetPassword;
use App\Models\User;
use App\Models\UserPasswordReset;
use App\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ResetPasswordController extends Controller
{
    /**
     * Show the reset password view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ResetPassword');
    }

    /**
     * Updates the user with the new password
     */
    public function store(ResetPassword $request): RedirectResponse
    {
        //
        // Retrieve the password reset entry for the given token
        $reset = UserPasswordReset::firstWhere('token', $request->safe()->token);

        //
        // Set the new password
        User::where('email', $reset->email)->update([
            'password' => Hash::make($request->safe()->password),
        ]);

        //
        // Remove the database entry for the password reset
        UserPasswordReset::where('email', $reset->email)->delete();

        Notification::add(trans('auth.reset-password.success'));

        return redirect()->route('signin.create');
    }
}
