<?php

namespace CubeSystems\SoapClient\Client\Cache;

use Closure;
use CubeSystems\SoapClient\Client\Contracts\Response;
use Illuminate\Support\Facades\Cache;

class NeverCacheStrategy extends AbstractCacheStrategy
{
    public function __construct()
    {
        $this->cache = Cache::store('none');
    }

    public function cache(string $cacheKey, Closure $callback): Response
    {
        return $callback();
    }
}
