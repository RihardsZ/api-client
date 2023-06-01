<?php

namespace CubeSystems\ApiClient\Client\Responses;

use CubeSystems\ApiClient\Client\Contracts\Response;

abstract class AbstractResponse implements Response
{
    private array $rawData;

    private ResponseStatusInfo $responseStatusInfo;

    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function setRawData(array $rawData): static
    {
        $this->rawData = $rawData;

        return $this;
    }

    public function getStatusInfo(): ResponseStatusInfo
    {
        return $this->responseStatusInfo;
    }

    public function setStatusInfo(ResponseStatusInfo $statusInfo): static
    {
        $this->responseStatusInfo = $statusInfo;

        return $this;
    }
}
