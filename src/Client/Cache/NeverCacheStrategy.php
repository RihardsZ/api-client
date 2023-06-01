<?php

namespace CubeSystems\ApiClient\Client\Cache;

use Closure;
use CubeSystems\ApiClient\Client\Contracts\Response;
use Illuminate\Support\Facades\Cache;

class NeverCacheStrategy extends AbstractCacheStrategy
{
    public function __construct()
    {
        $this->cache = Cache::store(null);
    }

    public function cache(string $cacheKey, Closure $callback): Response
    {
        return $callback();
    }
}
