<?php

namespace CubeSystems\ApiClient\Events;

use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use Illuminate\Foundation\Events\Dispatchable;

class ApiDebugEvent
{
    use Dispatchable;

    public function __construct(
        private Method $method,
        private Payload $payload,
        private Response $response,
        private CallStats $callStats
    ) {}

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getCallStats(): CallStats
    {
        return $this->callStats;
    }
}
