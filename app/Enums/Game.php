<?php

namespace App\Enums;

use App\Concerns\HasDisplayName;

enum Game: string
{
    use HasDisplayName;

    case checkers = 'checkers';
    case chess = 'chess';
    case go = 'go';
    case texasHoldem = 'texas-holdem';
    case wordle = 'wordle';

    /**
     * Gets the title of the game.
     */
    public function title(): string
    {
        return trans('games.'.$this->value.'.title');
    }

    /**
     * Gets the short description of the game.
     */
    public function shortDescription(): string
    {
        return trans('games.'.$this->value.'.short-description');
    }

    /**
     * Gets the description of the game.
     */
    public function description(): string
    {
        return trans('games.'.$this->value.'.description');
    }

    /**
     * Gets the number of players that can play the game.
     */
    public function players(): array
    {
        return match ($this) {
            self::checkers => [2],
            self::chess => [2],
            self::go => [2],
            self::texasHoldem => [2, 9],
            self::wordle => [1],
        };
    }

    /**
     * Gets the game type.
     */
    public function type(): GameType
    {
        return match ($this) {
            self::checkers => GameType::boardGame,
            self::chess => GameType::boardGame,
            self::go => GameType::boardGame,
            self::texasHoldem => GameType::cardGame,
            self::wordle => GameType::arcade,
        };
    }


    /**
     * Whether the game requires authentication.
     */
    public function requiresAuthentication(): bool
    {
        return match ($this) {
            self::checkers => true,
            self::chess => true,
            self::go => true,
            self::texasHoldem => true,
            self::wordle => false,
        };
    }

    /**
     * Gets different trivia about the game.
     */
    public function trivia(): array
    {
        $translator = app('translator');

        $trivia = [];

        $index = 0;
        while ($translator->has('games.'.$this->value.'.trivia.'.$index)) {
            $trivia[] = $translator->get('games.'.$this->value.'.trivia.'.$index);

            $index++;
        }

        return $trivia;
    }

    /**
     * Gets the meta information for the game.
     */
    public function meta(): array
    {
        return [
            'request_session_url' => match ($this) {
                self::wordle => route('sessions.wordle.store'),
                default => null,
            },
        ];
    }

    /**
     * Gets the base model class for the game.
     */
    public function modelClass(): string
    {
        return match ($this) {
            self::checkers => \App\Models\Games\Wordle::class,
            self::chess => \App\Models\Games\Wordle::class,
            self::go => \App\Models\Games\Wordle::class,
            self::texasHoldem => \App\Models\Games\Wordle::class,
            self::wordle => \App\Models\Games\Wordle::class,
        };
    }
}
