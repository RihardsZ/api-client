<?php

namespace CubeSystems\ApiClient\Client\Methods;

use CodeDredd\Soap\Client\Response as RawResponse;

use CubeSystems\ApiClient\Client\Contracts\CacheStrategy;
use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Contracts\Service;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromCache;
use CubeSystems\ApiClient\Events\ApiCalled;

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

        $response = $this->retrieveFromRemote($payload);

        if (!$response->getStatusInfo()->isTechnicalError()) {
            $this->cacheStrategy->cache($payload->getCacheKey(), function () use ($response) {
                return $response;
            });
        }

        return $response;
    }

    public function getName(): string
    {
        return static::METHOD_NAME;
    }

    public function getService(): Service
    {
        return $this->service;
    }

    abstract protected function toResponse(array $rawResponse, int $httpCode): Response;

    private function retrieveFromCache(Payload $payload): Response
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
        $microtimeFrom = microtime(true);

        $rawResponse = $this->service->getClient()->call(
            $this->getName(),
            $payload->toArray()
        );

        $microtimeTo = microtime(true);

        $response = $this->toResponse($rawResponse->json(), $rawResponse->status());

        $this->dispatchServiceCalledEvent(
            $payload,
            $rawResponse,
            $response,
            $microtimeFrom,
            $microtimeTo
        );

        return $response;
    }

    private function dispatchServiceCalledEvent(
        Payload $payload,
        RawResponse $rawResponse,
        Response $response,
        float $microtimeFrom,
        float $microtimeTo
    ): void {
        $debugInfo = $this->service->getClient()->debugLastSoapRequest();

        $stats = $this->makeCallStats(
            $rawResponse,
            $debugInfo,
            $microtimeFrom,
            $microtimeTo
        );

        ApiCalled::dispatch(
            $this,
            $payload,
            $response,
            $stats
        );
    }

    protected function makeCallStats(
        RawResponse $rawResponse,
        array $debugInfo,
        float $microtimeFrom,
        float $microtimeTo
    ): CallStats {
        $stats = new CallStats();

        $stats
            ->setRequestString($debugInfo['request']['body'])
            ->setRequestHeaders(collect($rawResponse->transferStats->getRequest()->getHeaders()))
            ->setResponseString($debugInfo['response']['body'])
            ->setResponseHeaders(collect($rawResponse->transferStats->getResponse()->getHeaders()))
            ->setMicrotimeStart($microtimeFrom)
            ->setMicrotimeFinish($microtimeTo)
            ->setTransferStats($rawResponse->transferStats);

        return $stats;
    }
}
