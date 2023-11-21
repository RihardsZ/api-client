<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Plugs;

use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Plugs\PlugManager;
use CubeSystems\ApiClient\Client\Plugs\PlugResponseInterface;
use Illuminate\Support\Arr;

class TestPlugManager extends PlugManager
{
    public function __construct(private array $plugs = [])
    {
    }

    public function findPlugForMethod(string $methodName, Payload $payload): ?PlugResponseInterface
    {
        return Arr::get($this->plugs, $methodName);
    }
}
