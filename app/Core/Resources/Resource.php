<?php

namespace App\Core\Resources;

use Closure;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Collection as IlluminateCollection;

class Resource extends JsonResource
{
    use HandlesResourceProperties;

    /**
     * {@inheritDoc}
     *
     * @return \App\Core\Resources\Collection
     */
    public static function collection($resource)
    {
        /** @var \App\Core\Resources\Collection */
        $collection = parent::collection($resource);

        return $collection;
    }

    /**
     * {@inheritDoc}
     *
     * @return \App\Core\Resources\Collection
     */
    protected static function newCollection($resource)
    {
        return new Collection($resource, static::class);
    }

    /**
     * Filter the properties of the resource.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function filterProperties(array $data): array
    {
        return collect($data)
            ->when(count($this->only), fn (IlluminateCollection $collection) => $collection->only($this->only))
            ->when(count($this->except), fn (IlluminateCollection $collection) => $collection->except($this->except))
            ->when(count($this->add), function (IlluminateCollection $collection) {

                $additional = [];
                foreach ($this->add as $key => $value) {
                    if ($value instanceof Closure) {
                        $additional = array_merge($additional, $value($this));
                    } else {
                        $additional[$key] = $value;
                    }
                }

                return $collection->merge($additional);
            })
            ->toArray();
    }

    /**
     * {@inheritDoc}
     *
     * @return array<string, mixed>
     */
    public function resolve($request = null)
    {
        $data = parent::resolve($request);

        return $this->filterProperties($data);
    }

    /**
     * Determine if the resource has a relationship loaded.
     *
     * @return \Illuminate\Http\Resources\MissingValue|mixed
     */
    public function whenLoadedNested(string $relation): mixed
    {
        $relationships = explode('.', $relation);
        $carry = $this->resource;
        while ($relationship = array_shift($relationships)) {
            if (! $carry->relationLoaded($relationship)) {
                return new MissingValue;
            }

            $carry = $carry->{$relationship};
        }

        return $carry;
    }
}
