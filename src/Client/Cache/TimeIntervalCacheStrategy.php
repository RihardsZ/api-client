<?php

namespace CubeSystems\SoapClient\Client\Cache;

use Closure;
use CubeSystems\SoapClient\Client\Contracts\Response;
use Illuminate\Support\Facades\Cache;

class TimeIntervalCacheStrategy extends AbstractCacheStrategy
{
    private const DEFAULT_CACHING_TIME_SECONDS = 10 * 60;

    public function __construct()
    {
        // note that if no persistent cache is configured this won't create one
        $defaultStore = config('cache.default');

        $this->cache = Cache::store($defaultStore);
    }

    public function cache(string $cacheKey, Closure $callback): Response
    {
        return $this->getCache()->remember($cacheKey, self::DEFAULT_CACHING_TIME_SECONDS, $callback);
    }
}
