<?php

namespace CubeSystems\SoapClient\Client;

use CodeDredd\Soap\SoapClient as BaseClient;
use CubeSystems\SoapClient\Client\Middlewares\SoapHeaderMiddleware;
use Illuminate\Support\Collection;

class SoapClient extends BaseClient
{
    public function withSoapHeaders(Collection $headers): static
    {
        $this->middlewares = array_merge_recursive($this->middlewares, [
            new SoapHeaderMiddleware($headers)
        ]);

        return $this;
    }
}
