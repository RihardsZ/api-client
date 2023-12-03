<?php

namespace CubeSystems\ApiClient\Client\Plugs;

use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Plug;

class PlugManager
{
    public function findPlugForMethod(string $methodName, Payload $payload): ?Plug
    {
        return null;
    }
}
