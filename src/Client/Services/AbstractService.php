<?php

namespace CubeSystems\SoapClient\Client\Services;

use CubeSystems\SoapClient\Client\SoapClient;
use CubeSystems\SoapClient\Client\Contracts\Endpoint;
use CubeSystems\SoapClient\Client\Contracts\Service;
use CubeSystems\SoapClient\Facades\Soap;
use Illuminate\Support\Collection;

abstract class AbstractService implements Service
{
    protected const SERVICE_PATH = '';

    private Endpoint $endpoint;

    private SoapClient $client;

    public function __construct(Endpoint $endpoint, Collection $headers)
    {
        $this->endpoint = $endpoint;
        $this->client = Soap::baseWsdl($this->getFullPath())
            ->withSoapHeaders($headers)
            ->withOptions([
                'trace' => true
            ]);
    }

    public function getClient(): SoapClient
    {
        return $this->client;
    }

    private function getFullPath(): string
    {
        return $this->endpoint->getWsdlUrl($this->getPath());
    }

    private function getPath(): string
    {
        return static::SERVICE_PATH;
    }
}
