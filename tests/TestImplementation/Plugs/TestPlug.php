<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Plugs;

use CubeSystems\ApiClient\Client\Contracts\Plug;

class TestPlug implements Plug
{
    public function __construct(private array $response)
    {
    }

    public function getResponse(): array
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return 200;
    }
}
