<?php

namespace App\Providers;

use App\Enums\Game;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        JsonResource::withoutWrapping();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // Prevent script preloading as it throws warnings in the console
        Vite::usePreloadTagAttributes(fn () => false);

        Relation::enforceMorphMap(
            collect(Game::cases())
                ->mapWithKeys(fn (Game $game) => [ $game->value => $game->modelClass() ])
                ->toArray()
        );

        Model::unguard();
    }
}
