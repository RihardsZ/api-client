<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods;

use CubeSystems\ApiClient\Client\Cache\RequestCacheStrategy;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestService;

class TestMethodWithRequestCache extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithRequestCache';

    public function __construct(
        TestService $service,
        RequestCacheStrategy $cacheStrategy
    ) {
        parent::__construct($service, $cacheStrategy);
    }
}
