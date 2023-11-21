<?php

namespace CubeSystems\ApiClient\Client\Cache;

use Closure;
use CubeSystems\ApiClient\Client\Contracts\CacheStrategy;
use CubeSystems\ApiClient\Client\Contracts\Response;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;

abstract class AbstractCacheStrategy implements CacheStrategy
{
    private const HIERARCHY_KEY = self::class . '_CACHE_HIERARCHY';

    protected Repository $cache;

    public function getCache(): Repository
    {
        return $this->cache;
    }

    abstract public function cache(string $cacheKey, Closure $callback): Response;

    public function addToHierarchy(string $hierarchicalKey, string $cacheKey): void
    {
        $hierarchy = $this->getHierarchy();

        $hierarchy[$hierarchicalKey][] = $cacheKey;

        $this->saveHierarchy($hierarchy);
    }

    public function forgetHierarchy(string $hierarchicalKey): void
    {
        $hierarchy = $this->getHierarchy();

        foreach (Arr::get($hierarchy, $hierarchicalKey, []) as $cacheKey) {
            $this->cache->forget($cacheKey);
        }

        $hierarchy[$hierarchicalKey] = [];

        $this->saveHierarchy($hierarchy);
    }

    private function getHierarchy(): array
    {
        return $this->getCache()->get(self::HIERARCHY_KEY, []);
    }

    private function saveHierarchy(array $hierarchy): void
    {
        $this->cache->forever(self::HIERARCHY_KEY, $hierarchy);
    }
}
