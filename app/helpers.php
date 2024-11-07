<?php

if (! function_exists('user')) {

    /**
     * Gets the currently authenticated user instance
     */
    function user(): \App\Models\User
    {
        if (auth()->guest()) {
            throw new LogicException('Access to global [user()] object while being in an unauthenticated scope.');
        }

        return auth()->user();
    }
}
