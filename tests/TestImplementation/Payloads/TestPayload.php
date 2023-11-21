<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Payloads;

use CubeSystems\ApiClient\Client\Payloads\AbstractPayload;

class TestPayload extends AbstractPayload
{
    private string $parameter = '';

    private bool $isCacheUsed = true;
    private bool $isCacheHierarchyUsed = false;

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

    protected function getUnprefixedCacheKey(): string
    {
        return self::class . ':' .$this->parameter;
    }

    public function isCacheRetrievalAllowed(): bool
    {
        return $this->isCacheUsed;
    }

    public function setUseCache(bool $isCacheUsed): TestPayload
    {
        $this->isCacheUsed = $isCacheUsed;

        return $this;
    }

    public function isUsingCacheHierarchy(): bool
    {
        return $this->isCacheHierarchyUsed;
    }

    public function setUseCacheHierarchy(bool $isCacheHierarchyUsed): TestPayload
    {
        $this->isCacheHierarchyUsed = $isCacheHierarchyUsed;

        return $this;
    }
}
