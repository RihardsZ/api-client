<?php

namespace CubeSystems\SoapClient\Client\Cache;

use Closure;
use CubeSystems\SoapClient\Client\Contracts\CacheStrategy;
use CubeSystems\SoapClient\Client\Contracts\Response;
use Illuminate\Contracts\Cache\Repository;

abstract class AbstractCacheStrategy implements CacheStrategy
{
    private Repository $cache;

    public function __construct()
    {
        $this->cache = $this->getCacheRepository();
    }

    public function getCache(): Repository
    {
        return $this->cache;
    }

    abstract protected function getCacheRepository(): Repository;

    abstract public function cache(string $cacheKey, Closure $callback): Response;
}
