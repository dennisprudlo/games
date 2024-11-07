<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Enums\Game
 */
class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->value,
            'title' => $this->title(),
            'short_description' => $this->shortDescription(),
            'description' => $this->description(),
            'players' => $this->players(),
            'type' => [
                'id' => $this->type(),
                'title' => $this->type()->title(),
                'icon' => $this->type()->icon(),
            ],
            'requires_authentication' => $this->requiresAuthentication(),
            'starred' => false,
            'trivia' => $this->trivia(),
        ];
    }
}
