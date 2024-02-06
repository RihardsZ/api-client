<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Client\Endpoints;

use CubeSystems\ApiClient\Client\Contracts\Endpoint;

abstract class AbstractSoapEndpoint implements Endpoint
{
    private const WSDL_URL_PATTERN = "%s/%s?wsdl";

    public function __construct(
        protected string $baseUrl,
    ) {
    }

    public function getAbsoluteUrl(string $path): string
    {
        return sprintf(
            self::WSDL_URL_PATTERN,
            $this->baseUrl,
            $path
        );
    }
}
