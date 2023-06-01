<?php

namespace CubeSystems\ApiClient\Client\Cache;

use Closure;
use CubeSystems\ApiClient\Client\Contracts\Response;
use Illuminate\Support\Facades\Cache;

class TimeIntervalCacheStrategy extends AbstractCacheStrategy
{
    private const DEFAULT_CACHING_TIME_SECONDS = 10 * 60;

    private int $cachingTimeSeconds = self::DEFAULT_CACHING_TIME_SECONDS;

    public function __construct()
    {
        // note that if no persistent cache is configured this won't create one
        $defaultStore = config('cache.default');

        $this->cache = Cache::store($defaultStore);
    }

    public function cache(string $cacheKey, Closure $callback): Response
    {
        return $this->getCache()->remember($cacheKey, $this->cachingTimeSeconds, $callback);
    }

    public function setCachingTimeSeconds(int $cachingTimeSeconds): TimeIntervalCacheStrategy
    {
        $this->cachingTimeSeconds = $cachingTimeSeconds;

        return $this;
    }
}
