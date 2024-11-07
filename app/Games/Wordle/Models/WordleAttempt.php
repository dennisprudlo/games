<?php

namespace App\Games\Wordle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \App\Games\Wordle\Models\Wordle $wordle
 */
class WordleAttempt extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'g_wordle_attempts';

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
     * The relationship to the attempts wordle game.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Games\Wordle\Models\Wordle,\App\Games\Wordle\Models\WordleAttempt>
     */
    public function wordle(): BelongsTo
    {
        return $this->belongsTo(Wordle::class)->withTrashed();
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
}
