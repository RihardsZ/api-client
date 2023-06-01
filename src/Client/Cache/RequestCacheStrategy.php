<?php

namespace CubeSystems\ApiClient\Client\Cache;

use Closure;
use CubeSystems\ApiClient\Client\Contracts\Response;
use Illuminate\Support\Facades\Cache;

class RequestCacheStrategy extends AbstractCacheStrategy
{
    public function __construct()
    {
        $this->cache = Cache::store('array');
    }

    public function cache(string $cacheKey, Closure $callback): Response
    {
        return $this->getCache()->rememberForever($cacheKey, $callback);
    }
}
