<?php

namespace App;

use Illuminate\Support\Facades\Session;

class Notification
{
    /**
     * Adds a new notification message to the session
     */
    public static function add(string $message, bool $error = false): void
    {
        $notifications = Session::get('notifications', []);

        array_push($notifications, [
            'uuid' => (string) str()->uuid(),
            'message' => $message,
            'error' => $error,
        ]);

        Session::put('notifications', $notifications);
    }

    /**
     * Adds a new notification error message to the session
     */
    public static function addError(string $message): void
    {
        self::add($message, true);
    }

    /**
     * Gets the notification data
     */
    public static function get(): array
    {
        return Session::pull('notifications', []);
    }
}
