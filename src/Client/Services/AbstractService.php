<?php

namespace CubeSystems\ApiClient\Client\Services;

use CodeDredd\Soap\SoapClient as BaseClient;
use CubeSystems\ApiClient\Client\Headers\Header;
use CubeSystems\ApiClient\Client\ApiClient;
use CubeSystems\ApiClient\Client\Contracts\Endpoint;
use CubeSystems\ApiClient\Client\Contracts\Service;
use Illuminate\Support\Collection;

abstract class AbstractService implements Service
{
    protected const SERVICE_PATH = '';

    protected BaseClient $client;

    public function __construct(
        private Endpoint $endpoint,
        /** @var Collection<Header> */
        private Collection $headers,
        ApiClient $client
    ) {
        $this->client = $client
            ->baseWsdl($this->getUrl())
            ->withSoapHeaders($headers);
    }

    public function getClient(): ApiClient
    {
        return $this->client;
    }

    public function getHeaders(): Collection
    {
        return $this->headers;
    }

    public function getUrl(): string
    {
        return $this->endpoint->getWsdlUrl($this->getPath());
    }

    private function getPath(): string
    {
        return static::SERVICE_PATH;
    }
}
