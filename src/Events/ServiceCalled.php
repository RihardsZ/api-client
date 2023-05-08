<?php

namespace CubeSystems\SoapClient\Events;

use CubeSystems\SoapClient\Client\Contracts\Method;
use CubeSystems\SoapClient\Client\Contracts\Payload;
use CubeSystems\SoapClient\Client\Contracts\Response;
use CubeSystems\SoapClient\Client\Stats\CallStats;
use Illuminate\Foundation\Events\Dispatchable;

class ServiceCalled
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
