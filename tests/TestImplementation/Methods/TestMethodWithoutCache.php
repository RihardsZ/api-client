<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods;

use CubeSystems\ApiClient\Client\Cache\NeverCacheStrategy;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestService;

class TestMethodWithoutCache extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithoutCache';

    public function __construct(
        TestService $service,
        NeverCacheStrategy $cacheStrategy
    ) {
        parent::__construct($service, $cacheStrategy);
    }
}
