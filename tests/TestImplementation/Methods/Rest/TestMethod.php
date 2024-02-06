<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Methods\Rest;

use CubeSystems\ApiClient\Client\Methods\AbstractRestMethod;
use CubeSystems\ApiClient\Client\Responses\ResponseStatusInfo;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestEntity;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestResponse;
use GuzzleHttp\TransferStats;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TestMethod extends AbstractRestMethod
{
    protected function toResponse(array $rawResponse, array $rawHeaders, int $httpCode): TestResponse
    {
        $response = new TestResponse();
        $response->setRawData($rawResponse);

        $status = new ResponseStatusInfo();

        $statusCode = Arr::get($rawResponse, 'status');

        if ($httpCode !== SymfonyResponse::HTTP_OK || $statusCode === 'T') {
            $status->setTechnicalErrorStatus();
            $response->setStatusInfo($status);

            return $response;
        }

        $status->setStatus($statusCode);

        $response->setStatusInfo($status);

        if ($status->isError()) {
            return $response;
        }

        $testEntity = new TestEntity();
        $testEntity->setName(Arr::get($rawResponse, 'name'));
        $testEntity->setAge((int) Arr::get($rawResponse, 'age'));
        $response->setEntity($testEntity);

        return $response;
    }

    protected function makeCallStats(
        TransferStats $transferStats,
        float $microtimeFrom,
        float $microtimeTo
    ): CallStats {
        return new CallStats();
    }
}
