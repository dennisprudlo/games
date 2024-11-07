<?php

namespace App\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Model;

trait SelectableEntity
{
    /**
     * Get the cases for a selection.
     *
     * @return array<int, array{key: string|null, value: string}>
     */
    public static function forSelection(bool $nullable = false, ?Closure $modifier = null, ?Closure $filter = null, string $nullableLabel = ''): array
    {
        $baseQuery = self::query();

        if (isset($filter)) {
            $baseQuery = $filter($baseQuery);
        }

        $selection = $baseQuery->get()->map(fn (Model $model) => [
            'key' => $model->uid,
            'value' => isset($modifier) ? $modifier($model) : $model->name,
        ]);

        if ($nullable) {
            $selection = $selection->prepend([
                'key' => '',
                'value' => $nullableLabel,
            ]);
        }

        return $selection->values()->toArray();
    }
}
