<?php

namespace App\Core\Resources;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class Collection extends AnonymousResourceCollection
{
    use HandlesResourceProperties;

    /**
     * @param Closure|null
     */
    private ?Closure $mapWithKeys;

    /**
     * {@inheritDoc}
     *
     * @return array<int, mixed>
     */
    public function toArray(Request $request)
    {
        return $this->collection
            ->map->only($this->only)
            ->map->except($this->except)
            ->map->add($this->add)
            ->map->configure($this->options)
            ->map(fn ($resource) => $resource->filterProperties($resource->toArray($request)))
            ->when(isset($this->mapWithKeys), fn ($collection) => $collection->mapWithKeys($this->mapWithKeys))
            ->all();
    }

    /**
     * Customize the pagination information for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array<mixed>  $paginated
     * @param  array<mixed>  $default
     * @return array<mixed>
     */
    public function paginationInformation($request, $paginated, $default): array
    {
        unset($default['meta']['links']);

        return $default;
    }

    /**
     * Adds a map with keys filter to the end of the filter chain.
     */
    public function mapWithKeys(Closure $callback): self
    {
        $this->mapWithKeys = $callback;

        return $this;
    }
}
