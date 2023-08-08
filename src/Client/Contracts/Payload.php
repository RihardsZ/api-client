<?php

namespace CubeSystems\ApiClient\Client\Contracts;

interface Payload
{
    public function toArray(): array;

    public function getCacheKey(): string;

    public function isCacheRetrievalAllowed(): bool;
}
