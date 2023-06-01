<?php

namespace CubeSystems\ApiClient\Client\Cache;

use Closure;
use CubeSystems\ApiClient\Client\Contracts\CacheStrategy;
use CubeSystems\ApiClient\Client\Contracts\Response;
use Illuminate\Contracts\Cache\Repository;

abstract class AbstractCacheStrategy implements CacheStrategy
{
    protected Repository $cache;

    public function getCache(): Repository
    {
        return $this->cache;
    }

    abstract public function cache(string $cacheKey, Closure $callback): Response;
}
