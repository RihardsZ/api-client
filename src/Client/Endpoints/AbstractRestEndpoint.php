<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Client\Endpoints;

use CubeSystems\ApiClient\Client\Contracts\Endpoint;

abstract class AbstractRestEndpoint implements Endpoint
{
    private const REST_URL_PATTERN = "%s/%s";

    public function __construct(
        protected string $url,
    ) {
    }

    public function getAbsoluteUrl(string $path): string
    {
        return sprintf(
            self::REST_URL_PATTERN,
            $this->url,
            $path
        );
    }
}
