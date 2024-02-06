<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap;

use CubeSystems\ApiClient\Client\Cache\RequestCacheStrategy;
use CubeSystems\ApiClient\Client\Plugs\PlugManager;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestSoapService;

class TestMethodWithRequestCache extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithRequestCache';

    public function __construct(
        TestSoapService $service,
        RequestCacheStrategy $cacheStrategy,
        PlugManager $plugManager
    ) {
        parent::__construct($service, $cacheStrategy, $plugManager);
    }
}
