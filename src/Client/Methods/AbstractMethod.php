<?php

namespace CubeSystems\SoapClient\Client\Methods;

use CodeDredd\Soap\Client\Response as RawResponse;

use CubeSystems\SoapClient\Client\Contracts\CacheStrategy;
use CubeSystems\SoapClient\Client\Contracts\Method;
use CubeSystems\SoapClient\Client\Contracts\Payload;
use CubeSystems\SoapClient\Client\Contracts\Response;
use CubeSystems\SoapClient\Client\Contracts\Service;
use CubeSystems\SoapClient\Client\Stats\CallStats;
use CubeSystems\SoapClient\Events\ResponseRetrievedFromCache;
use CubeSystems\SoapClient\Events\ServiceCalled;

abstract class AbstractMethod implements Method
{
    private Service $service;

    private CacheStrategy $cacheStrategy;

    public function __construct(Service $service, CacheStrategy $cacheStrategy)
    {
        $this->service = $service;
        $this->cacheStrategy = $cacheStrategy;
    }

    public function call(Payload $payload): Response
    {
        $cachePresent = $this->cacheStrategy->getCache()->has($payload->getCacheKey());

        if ($cachePresent) {
            return $this->retrieveFromCache($payload);
        }

        return $this->retrieveFromRemote($payload);
    }

    public function getName(): string
    {
        return static::METHOD_NAME;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    abstract protected function toResponse(array $rawResponse): Response;

    private function retrieveFromCache(Payload $payload)
    {
        $response = $this->cacheStrategy->getCache()->get($payload->getCacheKey());

        $stats = new CallStats();
        $stats->setMicrotimeStart(microtime(true));

        ResponseRetrievedFromCache::dispatch(
            $this,
            $payload,
            $response,
            $stats
        );

        return $response;
    }

    private function retrieveFromRemote(Payload $payload): Response
    {
        return $this->cacheStrategy->cache($payload->getCacheKey(), function () use ($payload) {
            $microtimeFrom = microtime(true);

            $rawResponse = $this->service->getClient()->call(
                $this->getName(),
                $payload->toArray()
            );

            $microtimeTo = microtime(true);

            $response = $this->toResponse($rawResponse->json());

            $this->dispatchServiceCalledEvent(
                $payload,
                $rawResponse,
                $response,
                $microtimeFrom,
                $microtimeTo
            );

            return $response;
        });
    }

    private function dispatchServiceCalledEvent(
        Payload $payload,
        RawResponse $rawResponse,
        Response $response,
        float $microtimeFrom,
        float $microtimeTo
    ): void {
        $debugInfo = $this->service->getClient()->debugLastSoapRequest();

        $stats = new CallStats();
        $stats
            ->setRequestString($debugInfo['request']['body'])
            ->setRequestHeaders(collect($rawResponse->transferStats->getRequest()->getHeaders()))
            ->setResponseString($debugInfo['response']['body'])
            ->setResponseHeaders(collect($rawResponse->transferStats->getResponse()->getHeaders()))
            ->setMicrotimeStart($microtimeFrom)
            ->setMicrotimeFinish($microtimeTo)
            ->setTransferStats($rawResponse->transferStats);

        ServiceCalled::dispatch(
            $this,
            $payload,
            $response,
            $stats
        );
    }
}
