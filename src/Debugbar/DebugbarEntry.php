<?php

namespace CubeSystems\ApiClient\Debugbar;

use Carbon\Carbon;
use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Stats\CallStats;

class DebugbarEntry
{
    private Method $method;

    private CallStats $callStats;

    private Payload $payload;

    private Response $response;

    private bool $isCached = false;

    public function setMethod(Method $method): DebugbarEntry
    {
        $this->method = $method;

        return $this;
    }

    public function setCallStats(CallStats $callStats): DebugbarEntry
    {
        $this->callStats = $callStats;

        return $this;
    }

    public function setPayload(Payload $payload): DebugbarEntry
    {
        $this->payload = $payload;

        return $this;
    }

    public function setResponse(Response $response): DebugbarEntry
    {
        $this->response = $response;

        return $this;
    }

    public function setCached(bool $isCached = true): DebugbarEntry
    {
        $this->isCached = $isCached;

        return $this;
    }

    public function isCached(): bool
    {
        return $this->isCached;
    }

    public function toArray(): array
    {
        $startTime = Carbon::createFromTimestamp($this->callStats->getMicrotimeStart())->toDateTimeString();

        return [
            'isCached' => $this->isCached,
            'method' => $this->method->getName(),
            'service' => get_class($this->method->getService()),
            'request' => $this->isCached ? '' : $this->callStats->getRequestString(),
            'executionTime' => $this->isCached ? '' : $this->callStats->getMicrotimeDifference(),
            'startTime' => $startTime
        ];
    }
}
