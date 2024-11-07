<?php

namespace App\Games\Wordle;

use App\Games\Wordle\Models\Wordle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Controller
{
    /**
     * Requests a new wordle game session.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\Games\Wordle */
        $wordle = Wordle::query()->create([
            'word' => str()->random(5),
            'created_by' => $request->user()?->id,
        ]);

        return redirect()->route('sessions.wordle.show', $wordle);
    }

    /**
     * Shows the wordle game session.
     */
    public function show(Request $request, Wordle $wordle): Response
    {
        return Inertia::render('Games/Wordle', [
            'wordle' => $wordle,
        ]);
    }
}
