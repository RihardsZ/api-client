<?php

namespace CubeSystems\SoapClient\Client\Methods;

use CubeSystems\SoapClient\Client\Contracts\CacheStrategy;
use CubeSystems\SoapClient\Client\Contracts\Payload;
use CubeSystems\SoapClient\Client\Contracts\Response;
use CubeSystems\SoapClient\Client\Contracts\Service;

abstract class AbstractMethod
{
    private Service $service;

    private CacheStrategy $cacheStrategy;

    protected const METHOD_NAME = '';

    public function __construct(Service $service, CacheStrategy $cacheStrategy)
    {
        $this->service = $service;
        $this->cacheStrategy = $cacheStrategy;
    }

    public function call(Payload $payload): Response
    {
        return $this->cacheStrategy->cache($payload->getCacheKey(), function () use ($payload) {
            $rawResult = $this->service->getClient()->call(
                $this->getName(),
                $payload->toArray()
            );

            $associativeArray = $rawResult->json();

            return $this->toResponse($associativeArray);
        });
    }

    private function getName(): string
    {
        return static::METHOD_NAME;
    }

    abstract protected function toResponse(array $rawResponse): Response;
}
