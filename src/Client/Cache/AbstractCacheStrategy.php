<?php

namespace CubeSystems\SoapClient\Client\Cache;

use Closure;
use CubeSystems\SoapClient\Client\Contracts\CacheStrategy;
use CubeSystems\SoapClient\Client\Contracts\Response;
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
