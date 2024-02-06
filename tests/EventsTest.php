<?php

declare(strict_types=1);

use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromCache;
use CubeSystems\ApiClient\Events\ApiCalled;

it('gets what it sets for ServiceCalled', function () {
    $method = Mockery::mock(Method::class);
    $payload = Mockery::mock(Payload::class);
    $response = Mockery::mock(Response::class);
    $stats = Mockery::mock(CallStats::class);

    $event = new ApiCalled($method, $payload, $response, $stats);

    expect($event->getMethod())->toBe($method)
        ->and($event->getPayload())->toBe($payload)
        ->and($event->getResponse())->toBe($response)
        ->and($event->getStats())->toBe($stats);
});

it('gets what it sets for ResponseRetrievedFromCache', function () {
    $method = Mockery::mock(Method::class);
    $payload = Mockery::mock(Payload::class);
    $response = Mockery::mock(Response::class);
    $stats = Mockery::mock(CallStats::class);

    $event = new ResponseRetrievedFromCache($method, $payload, $response, $stats);

    expect($event->getMethod())->toBe($method)
        ->and($event->getPayload())->toBe($payload)
        ->and($event->getResponse())->toBe($response)
        ->and($event->getCallStats())->toBe($stats);
});
