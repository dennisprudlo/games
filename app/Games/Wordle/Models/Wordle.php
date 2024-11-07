<?php

namespace App\Games\Wordle\Models;

use App\Concerns\UniqueId;
use App\Contracts\ReferenceAware;
use App\Exceptions\GameLogicException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \Illuminate\Database\Eloquent\Collection<int,\App\Games\Wordle\Models\WordleAttempt> $attempts
 */
class Wordle extends Model implements ReferenceAware
{
    use SoftDeletes;
    use UniqueId;

    /**
     * {@inheritDoc}
     */
    protected $table = 'g_wordle';

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The relationship to the wordles attempts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Games\Wordle\Models\WordleAttempt>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(WordleAttempt::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Checks if the game is completed.
     */
    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Attempts to guess a word.
     *
     * @param string $word
     *
     * @throws \App\Exceptions\GameLogicException
     */
    public function attempt(string $word): WordleAttempt
    {
        throw_if(strlen($word) !== 5, new GameLogicException('Word must be 5 characters long.'));
        throw_if(! ctype_alpha($word), new GameLogicException('Word must be alphabetic.'));
        throw_if($this->isCompleted(), new GameLogicException('Game already completed.'));
        throw_if($this->attempts()->count() >= 5, new GameLogicException('No more attempts allowed.'));

        $attempt = $this->attempts()->create([
            'attempt' => substr($word, 0, 5),
            'guessed_at' => now(),
        ]);

        if ($this->attempts()->count() === 5) {
            $this->update(['completed_at' => now()]);
        }

        return $attempt;
    }

    /*
    |--------------------------------------------------------------------------
    | Implementations
    |--------------------------------------------------------------------------
    */

    /**
     * {@inheritDoc}
     */
    public function deleteWithRefs(): void
    {
        $this->delete();
    }
}
