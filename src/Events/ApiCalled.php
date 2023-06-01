<?php

namespace CubeSystems\ApiClient\Events;

use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use Illuminate\Foundation\Events\Dispatchable;

class ApiCalled
{
    use Dispatchable;

    public function __construct(
        private Method $method,
        private Payload $payload,
        private Response $response,
        private CallStats $stats
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

    public function getStats(): CallStats
    {
        return $this->stats;
    }
}
