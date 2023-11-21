<?php

namespace CubeSystems\ApiClient\Client\Plugs;

use CubeSystems\ApiClient\Client\Contracts\Payload;

class PlugManager
{
    public function findPlugForMethod(string $methodName, Payload $payload): ?PlugResponseInterface
    {
        return null;
    }
}
