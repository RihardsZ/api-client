<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Payloads;

use CubeSystems\ApiClient\Client\Payloads\AbstractPayload;

class TestPayload extends AbstractPayload
{
    private string $parameter;

    public function setParameter(string $parameter): TestPayload
    {
        $this->parameter = $parameter;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'parameter' => $this->parameter
        ];
    }

    public function getCacheKey(): string
    {
        return self::class . $this->parameter;
    }
}
