<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods;

use CubeSystems\ApiClient\Client\Cache\TimeIntervalCacheStrategy;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestService;

class TestMethodWithTimeIntervalCache extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithTimeIntervalCache';

    public function __construct(
        TestService $service,
        TimeIntervalCacheStrategy $cacheStrategy
    ) {
        parent::__construct($service, $cacheStrategy);
    }
}
