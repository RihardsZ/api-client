<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Plugs;

use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Plugs\PlugResponseInterface;

class TestPlug implements PlugResponseInterface
{
    public function __construct(private TestPlugResponse $response)
    {
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getStatusCode(): int
    {
        return 200;
    }
}
