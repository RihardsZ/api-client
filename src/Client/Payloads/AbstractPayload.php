<?php

namespace CubeSystems\ApiClient\Client\Payloads;

use CubeSystems\ApiClient\Client\Cache\CachePrefix;
use CubeSystems\ApiClient\Client\Contracts\Payload;

abstract class AbstractPayload implements Payload
{
    public function __construct(
        protected ?CachePrefix $cachePrefix = null
    ) {
    }

    public function isCacheRetrievalAllowed(): bool
    {
        return true;
    }

    public function isUsingCacheHierarchy(): bool
    {
        return false;
    }

    public function getCacheKey(): string
    {
        $fullKey = sprintf(
            "%s:%s",
            $this->getCachePrefixString(),
            $this->getUnprefixedCacheKey()
        );

        return md5($fullKey);
    }

    public function getCacheHierarchyKey(): string
    {
        if (!$this->cachePrefix || !$this->isUsingCacheHierarchy()) {
            return '';
        }

        return sprintf(
            "%s:%s",
            $this->getCachePrefixString(),
            $this->getCacheTag()
        );
    }

    protected function getCacheTag(): string
    {
        return static::class;
    }

    abstract protected function getUnprefixedCacheKey(): string;

    private function getCachePrefixString(): string
    {
        return $this->cachePrefix?->getPrefixString() ?? '';
    }
}
