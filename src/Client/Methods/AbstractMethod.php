<?php

namespace CubeSystems\ApiClient\Client\Methods;

use CubeSystems\ApiClient\Client\Contracts\CacheStrategy;
use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Plug;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Contracts\Service;
use CubeSystems\ApiClient\Client\Plugs\PlugManager;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromCache;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromPlug;
use GuzzleHttp\TransferStats;

abstract class AbstractMethod implements Method
{
    protected const METHOD_NAME = '';

    public function __construct(
        protected Service $service,
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
    abstract protected function retrieveFromRemote(Payload $payload): Response;

    abstract protected function toResponse(array $rawResponse, array $rawHeaders, int $httpCode): Response;

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
            $plug->getResponseHeaders(),
            $plug->getStatusCode(),
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

    protected function makeCallStats(
        TransferStats $transferStats,
        float $microtimeFrom,
        float $microtimeTo,
    ): CallStats {
        $stats = new CallStats();

        $request = $transferStats->getRequest();
        $response = $transferStats->getResponse();

        $stats
            ->setRequestString((string) $request->getBody())
            ->setRequestHeaders(collect($request->getHeaders()))
            ->setResponseString((string) $response?->getBody())
            ->setResponseHeaders(collect($response?->getHeaders()))
            ->setMicrotimeStart($microtimeFrom)
            ->setMicrotimeFinish($microtimeTo)
            ->setTransferStats($transferStats);

        return $stats;
    }
}
