<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap;

use CubeSystems\ApiClient\Client\Cache\RequestCacheStrategy;
use CubeSystems\ApiClient\Tests\TestImplementation\Plugs\TestPlugManager;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestSoapService;

class TestMethodWithPlug extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithPlug';

    public function __construct(
        TestSoapService $service,
        RequestCacheStrategy $cacheStrategy,
        TestPlugManager $plugManager
    ) {
        parent::__construct($service, $cacheStrategy, $plugManager);
    }
}
