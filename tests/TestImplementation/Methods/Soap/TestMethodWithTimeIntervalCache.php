<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap;

use CubeSystems\ApiClient\Client\Cache\TimeIntervalCacheStrategy;
use CubeSystems\ApiClient\Client\Plugs\PlugManager;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestSoapService;

class TestMethodWithTimeIntervalCache extends TestMethod
{
    protected const METHOD_NAME = 'TestMethodWithTimeIntervalCache';

    public function __construct(
        TestSoapService $service,
        TimeIntervalCacheStrategy $cacheStrategy,
        PlugManager $plugManager,
    ) {
        parent::__construct($service, $cacheStrategy, $plugManager);
        $cacheStrategy->setCachingTimeSeconds(1000);
    }
}
