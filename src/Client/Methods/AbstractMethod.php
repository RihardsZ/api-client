<?php

namespace CubeSystems\ApiClient\Client\Methods;

use CodeDredd\Soap\Client\Response as RawResponse;
use CubeSystems\ApiClient\Client\Contracts\CacheStrategy;
use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Plug;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Contracts\Service;
use CubeSystems\ApiClient\Client\Plugs\PlugManager;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Events\ApiCalled;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromCache;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromPlug;

abstract class AbstractMethod implements Method
{
    public function __construct(
        private Service $service,
        private CacheStrategy $cacheStrategy,
        private PlugManager $plugManager,
    ) {
    }

    public function call(Payload $payload): Response
    {
        if ($plug = $this->plugManager->findPlugForMethod($this->getName(), $payload)) {
            return $this->retrieveFromPlug($payload, $plug);
        }

        if ($this->isUsingCache($payload)) {
            return $this->retrieveFromCache($payload);
        }

        $response = $this->retrieveFromRemote($payload);

        if (!$response->getStatusInfo()->isTechnicalError()) {
            $this->cacheStrategy->cache($payload->getCacheKey(), function () use ($response) {
                return $response;
            });

            if ($payload->isUsingCacheHierarchy()) {
                $this->cacheStrategy->addToHierarchy($payload->getCacheHierarchyKey(), $payload->getCacheKey());
            }
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

    public function removeFromCache(Payload $payload): void
    {
        $this->cacheStrategy->getCache()->forget($payload->getCacheKey());
    }

    public function removeHierarchyFromCache(Payload $payload): void
    {
        $this->cacheStrategy->forgetHierarchy($payload->getCacheHierarchyKey());
    }

    abstract protected function toResponse(array $rawResponse, int $httpCode): Response;

    private function isUsingCache(Payload $payload): bool
    {
        if (!$payload->isCacheRetrievalAllowed()) {
            return false;
        }

        return $this->cacheStrategy->getCache()->has($payload->getCacheKey());
    }

    private function retrieveFromPlug(Payload $payload, Plug $plug): Response
    {
        $stats = new CallStats();
        $stats->setMicrotimeStart(microtime(true));

        $response = $this->toResponse(
            $plug->getResponse(),
            $plug->getStatusCode()
        );

        ResponseRetrievedFromPlug::dispatch(
            $this,
            $payload,
            $response,
            $stats
        );

        return $response;
    }

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

        $rawDataArray = $this->getRawDataArray($rawResponse);

        $response = $this->toResponse($rawDataArray, $rawResponse->status());

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

    private function getRawDataArray(RawResponse $rawResponse): array
    {
        $rawDataArray = $rawResponse->json();

        if (is_array($rawDataArray)) {
            return $rawDataArray;
        }

        return [
            'status' => [
                'code' => $rawResponse->status(),
                'message' => $rawResponse->body()
            ]
        ];
    }
}
