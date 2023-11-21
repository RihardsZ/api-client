<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Payloads;

use CubeSystems\ApiClient\Client\Payloads\AbstractPayload;

class MinimalisticTestPayload extends AbstractPayload
{
    public function toArray(): array
    {
        return [];
    }

    protected function getUnprefixedCacheKey(): string
    {
        return self::class;
    }
}
