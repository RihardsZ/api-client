<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods;

use CubeSystems\ApiClient\Client\Cache\RequestCacheStrategy;
use CubeSystems\ApiClient\Tests\TestImplementation\Plugs\TestPlugManager;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestService;

class TestMethodWithPlug extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithPlug';

    public function __construct(
        TestService $service,
        RequestCacheStrategy $cacheStrategy,
        TestPlugManager $plugManager
    ) {
        parent::__construct($service, $cacheStrategy, $plugManager);
    }
}
