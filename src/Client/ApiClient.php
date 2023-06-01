<?php

namespace CubeSystems\ApiClient\Client;

use CodeDredd\Soap\SoapClient as BaseClient;
use CubeSystems\ApiClient\Client\Middlewares\SoapHeaderMiddleware;
use Illuminate\Support\Collection;

class ApiClient extends BaseClient
{
    public function withSoapHeaders(Collection $headers): static
    {
        $this->middlewares = array_merge_recursive($this->middlewares, [
            new SoapHeaderMiddleware($headers)
        ]);

        return $this;
    }
}
