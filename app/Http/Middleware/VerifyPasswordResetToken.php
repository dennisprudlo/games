<?php

namespace App\Http\Middleware;

use App\Models\UserPasswordReset;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyPasswordResetToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //
        // Check if a token is given
        $token = $request->route('token');
        if (! isset($token)) {
            abort(404);
        }

        //
        // Check if there is a password reset entry for the given token
        $passwordReset = UserPasswordReset::firstWhere('token', $token);
        if (! isset($passwordReset)) {
            abort(404);
        }

        //
        // Check if the reset token is expired
        if (now()->greaterThan($passwordReset->expires_at)) {
            UserPasswordReset::where('token', $token)->delete();

            throw new HttpException(403, 'Verification token expired.');
        }

        //
        // Token is given, valid and not expired
        return $next($request);
    }
}
