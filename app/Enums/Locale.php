<?php

namespace App\Enums;

use App\Concerns\HasDisplayName;

enum Locale: string
{
    use HasDisplayName;

    case english = 'en';

    /**
     * Check if the given locale is supported.
     */
    public static function supported(string $locale): bool
    {
        return collect(self::cases())->map->value->contains($locale);
    }
}
