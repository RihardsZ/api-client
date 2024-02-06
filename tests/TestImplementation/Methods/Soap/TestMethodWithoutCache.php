<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap;

use CubeSystems\ApiClient\Client\Cache\NeverCacheStrategy;
use CubeSystems\ApiClient\Client\Plugs\PlugManager;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestSoapService;

class TestMethodWithoutCache extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithoutCache';

    public function __construct(
        TestSoapService $service,
        NeverCacheStrategy $cacheStrategy,
        PlugManager $plugManager
    ) {
        parent::__construct($service, $cacheStrategy, $plugManager);
    }
}
