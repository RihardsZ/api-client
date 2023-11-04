<?php

namespace CubeSystems\ApiClient\Client;

use CodeDredd\Soap\SoapClient as BaseClient;
use CubeSystems\ApiClient\Client\Middlewares\SoapHeaderMiddleware;
use Illuminate\Support\Collection;

class ApiClient extends BaseClient
{
    public function withSoapHeaders(Collection $headers): static
    {
        // guard against adding the same middleware twice
        // because CodeDredd package internally shares many things,
        // and you have same middlewares for separate clients
        foreach($this->middlewares as $middleware) {
            if ($middleware instanceof SoapHeaderMiddleware) {
                return $this;
            }
        }

        $this->middlewares = array_merge_recursive($this->middlewares, [
            new SoapHeaderMiddleware($headers)
        ]);

        return $this;
    }
}
