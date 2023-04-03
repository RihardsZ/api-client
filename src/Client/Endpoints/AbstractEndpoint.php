<?php

namespace CubeSystems\SoapClient\Client\Endpoints;

use CubeSystems\SoapClient\Client\Contracts\Endpoint;

abstract class AbstractEndpoint implements Endpoint
{
    protected string $url;

    private const WSDL_URL_PATTERN = "%s/%s?wsdl";


    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function getWsdlUrl(string $path): string
    {
        return sprintf(
            self::WSDL_URL_PATTERN,
            $this->url,
            $path
        );
    }
}
