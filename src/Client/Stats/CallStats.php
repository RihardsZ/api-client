<?php

namespace CubeSystems\ApiClient\Client\Stats;

use GuzzleHttp\TransferStats;
use Illuminate\Support\Collection;

class CallStats
{
    private string $requestString;

    private Collection $requestHeaders;

    private string $responseString;

    private Collection $responseHeaders;

    private float $microtimeStart;

    private float $microtimeFinish;

    private TransferStats $transferStats;

    public function getRequestString(): string
    {
        return $this->requestString;
    }

    public function setRequestString(string $requestString): CallStats
    {
        $this->requestString = $requestString;

        return $this;
    }

    public function getRequestHeaders(): Collection
    {
        return $this->requestHeaders;
    }

    public function setRequestHeaders(Collection $requestHeaders): CallStats
    {
        $this->requestHeaders = $requestHeaders;

        return $this;
    }

    public function getResponseString(): string
    {
        return $this->responseString;
    }

    public function setResponseString(string $responseString): CallStats
    {
        $this->responseString = $responseString;

        return $this;
    }

    public function getResponseHeaders(): Collection
    {
        return $this->responseHeaders;
    }

    public function setResponseHeaders(Collection $responseHeaders): CallStats
    {
        $this->responseHeaders = $responseHeaders;

        return $this;
    }

    public function getMicrotimeStart(): float
    {
        return $this->microtimeStart;
    }

    public function setMicrotimeStart(float $microtimeStart): CallStats
    {
        $this->microtimeStart = $microtimeStart;

        return $this;
    }

    public function getMicrotimeFinish(): float
    {
        return $this->microtimeFinish;
    }

    public function setMicrotimeFinish(float $microtimeFinish): CallStats
    {
        $this->microtimeFinish = $microtimeFinish;

        return $this;
    }

    public function getTransferStats(): TransferStats
    {
        return $this->transferStats;
    }

    public function setTransferStats(TransferStats $transferStats): CallStats
    {
        $this->transferStats = $transferStats;

        return $this;
    }

    public function getMicrotimeDifference(): float
    {
        return $this->microtimeFinish - $this->microtimeStart;
    }
}
