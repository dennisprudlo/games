<?php

namespace App\Core\Resources;

use Closure;
use Illuminate\Support\Arr;

trait HandlesResourceProperties
{
    /**
     * The list of keys that should be returned.
     *
     * @var array<string>
     */
    public array $only = [];

    /**
     * The list of keys that should be excluded.
     *
     * @var array<string>
     */
    public array $except = [];

    /**
     * The data that should be added to the resource.
     *
     * @var array<string, mixed>
     */
    public array $add = [];

    /**
     * The resource options.
     *
     * @var array<string|int, mixed>
     */
    public array $options = [];

    /**
     * Scopes the resource to only return the given keys.
     *
     * @param  array<string>|string  $only
     */
    public function only(array|string $only): self
    {
        $this->only = Arr::wrap($only);

        return $this;
    }

    /**
     * Scopes the resource to exclude the given keys.
     *
     * @param  array<string>|string  $except
     */
    public function except(array|string $except): self
    {
        $this->except = Arr::wrap($except);

        return $this;
    }

    /**
     * Adds additional data to the resource.
     *
     * @param  array<string, mixed>|\Closure(mixed): array<string, mixed>  $data
     */
    public function add(array|Closure $data): self
    {
        if ($data instanceof Closure) {
            array_push($this->add, $data);

            return $this;
        }

        $this->add = array_merge($this->add, $data);

        return $this;
    }

    /**
     * Adds additional options to the resource.
     *
     * @param  array<string, mixed>  $options
     */
    public function configure(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Adds additional options to the resource.
     *
     * @param  string|array<string>|int|null  $key
     */
    public function option(string|array|int|null $key, mixed $default = null): mixed
    {
        return data_get($this->options, $key, $default);
    }
}
