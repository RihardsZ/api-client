<?php

namespace CubeSystems\SoapClient\Client\Cache;

use Closure;
use CubeSystems\SoapClient\Client\Contracts\Response;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

class NeverCacheStrategy extends AbstractCacheStrategy
{
    protected function getCacheRepository(): Repository
    {
        return Cache::store('none');
    }

    public function cache(string $cacheKey, Closure $callback): Response
    {
        return $callback();
    }
}
