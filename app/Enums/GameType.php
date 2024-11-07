<?php

namespace App\Enums;

use App\Concerns\HasDisplayName;

enum GameType: string
{
    use HasDisplayName;

    case arcade = 'arcade';
    case boardGame = 'board-game';
    case cardGame = 'card-game';

    /**
     * Get the title for the game type.
     */
    public function title(): string
    {
        return trans('games.general.types.'.$this->value);
    }

    /**
     * Get the icon class for the game type.
     */
    public function icon(): string
    {
        return match ($this) {
            self::arcade => 'fa-gamepad-modern',
            self::boardGame => 'fa-dice',
            self::cardGame => 'fa-cards',
        };
    }
}
