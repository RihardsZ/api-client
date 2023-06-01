<?php

namespace CubeSystems\ApiClient\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use CubeSystems\ApiClient\ApiClientServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ApiClientServiceProvider::class,
        ];
    }
}
