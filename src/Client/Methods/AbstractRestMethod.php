<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Client\Methods;

use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Events\ApiCalled;
use Illuminate\Http\Client\Response as RawResponse;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

abstract class AbstractRestMethod extends AbstractMethod
{
    protected const HTTP_METHOD = HttpRequest::METHOD_POST;

    final protected function retrieveFromRemote(Payload $payload): Response
    {
        $microtimeFrom = microtime(true);

        $rawResponse = Http::withOptions(
            $this->getService()->getOptions()->toArray(),
        )->{static::HTTP_METHOD}(
            $this->getUrl(),
            $payload->toArray()
        );

        $microtimeTo = microtime(true);

        $rawResponseArray = $rawResponse->json() ?? [$rawResponse->body()];

        $response = $this->toResponse($rawResponseArray, $rawResponse->headers(), $rawResponse->status());

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
        $stats = $this->makeCallStats(
            $rawResponse->transferStats,
            $microtimeFrom,
            $microtimeTo,
        );

        ApiCalled::dispatch(
            $this,
            $payload,
            $response,
            $stats
        );
    }

    private function getUrl(): string
    {
        return $this->getService()->getUrl() . '/' . static::METHOD_NAME;
    }
}
