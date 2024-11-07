<?php

namespace App\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasDisplayName
{
    /**
     * Returns the display name of the enum value.
     */
    public function displayName(): string
    {
        $key = Str::of(get_class($this))
            ->replace('\\', '/')
            ->basename()
            ->pluralStudly()
            ->kebab();

        return trans('enum-display-names.'.$key.'.'.$this->value);
    }

    /**
     * Get the cases for a selection.
     *
     * @return array<int, array{key: string|null, value: string}>
     */
    public static function forSelection(bool $nullable = false, array $only = [], array $except = [], array $valueMapping = []): array
    {
        /** @var \Illuminate\Support\Collection<int, array{key: string|null, value: string}> */
        $selection = (new Collection(self::cases()))
            ->when(count($only) > 0, fn (Collection $cases) => $cases->filter(fn (self $case) => in_array($case, $only)))
            ->when(count($except) > 0, fn (Collection $cases) => $cases->filter(fn (self $case) => ! in_array($case, $except)))
            ->map(fn (self $case) => [
                'key' => $case->value,
                'value' => $valueMapping[$case->value] ?? $case->displayName(),
            ]);

        if ($nullable) {
            $selection = $selection->prepend([
                'key' => null,
                'value' => '',
            ]);
        }

        return $selection->values()->toArray();
    }
}
