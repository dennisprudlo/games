<?php

namespace App\Http\Controllers;

use App\Enums\Game;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Shows the dashboard page
     */
    public function dashboard(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'games' => GameResource::collection(Game::cases()),
        ]);
    }

    /**
     * Shows the details page of a single game
     */
    public function game(Request $request, Game $game): Response
    {
        return Inertia::render('Game', [
            'game' => new GameResource($game),
        ]);
    }
}
