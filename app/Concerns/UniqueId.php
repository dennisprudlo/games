<?php

namespace App\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property string $uid
 */
trait UniqueId
{
    /**
     * Boots the unique id trait and registers setting
     * the unique id when creating an instance of the model.
     */
    protected static function bootUniqueId(): void
    {
        static::creating(fn (self $model) => $model->uid = ((string) Str::ulid()));
    }

    /**
     * Retrieve the model by the passed unique uid.
     */
    public static function uid(?string $uid = null): ?self
    {
        return static::firstWhere('uid', $uid);
    }

    /**
     * Gets the actual id's from a list of uids.
     *
     * @param  string|array<string>  $uids
     * @return array<int>
     */
    public static function keyFromUid(string|array $uids): array
    {
        $entries = [];
        foreach (Arr::wrap($uids) as $uid) {
            if (is_numeric($uid)) {
                continue;
            }

            if ($entry = self::firstWhere('uid', '=', $uid)) {
                $entries[] = $entry->id;
            }
        }

        return $entries;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uid';
    }

    /**
     * Scope a query to only include the models with the given uid
     *
     * @param  \Illuminate\Database\Eloquent\Builder<self>  $query
     * @param  array<string>|string  $uids
     * @return \Illuminate\Database\Eloquent\Builder<self>
     */
    public function scopeScopeUids(Builder $query, array|string $uids): Builder
    {
        return is_array($uids)
            ? $query->whereIn('uid', $uids)
            : $query->where('uid', $uids);
    }
}
