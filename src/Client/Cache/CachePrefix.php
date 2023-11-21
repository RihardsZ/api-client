<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Client\Cache;

class CachePrefix
{
    public function __construct(
        private string $prefixString
    ) {
    }

    public function getPrefixString(): string
    {
        return $this->prefixString;
    }
}
