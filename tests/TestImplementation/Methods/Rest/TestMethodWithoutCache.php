<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods\Rest;

use CubeSystems\ApiClient\Client\Cache\NeverCacheStrategy;
use CubeSystems\ApiClient\Tests\TestImplementation\Plugs\TestPlugManager;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestRestService;

class TestMethodWithoutCache extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithoutCache';

    public function __construct(
        TestRestService $service,
        NeverCacheStrategy $cacheStrategy,
        TestPlugManager $plugManager
    ) {
        parent::__construct($service, $cacheStrategy, $plugManager);
    }
}
