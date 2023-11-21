<?php

namespace CubeSystems\ApiClient\Client\Contracts;

use Closure;
use Illuminate\Contracts\Cache\Repository;

interface CacheStrategy
{
    public function cache(string $cacheKey, Closure $callback): Response;

    public function addToHierarchy(string $hierarchicalKey, string $cacheKey): void;

    public function forgetHierarchy(string $hierarchicalKey): void;

    public function getCache(): Repository;
}
