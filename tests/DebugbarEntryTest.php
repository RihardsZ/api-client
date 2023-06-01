<?php

use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Debugbar\DebugbarEntry;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\TestMethodWithoutCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestService;

beforeEach(function () {
    app()->singleton(TestEndpoint::class, function () {
        return new TestEndpoint('https://www.w3schools.com');
    });
});

it('says it\'s cached when it has been set cached', function () {
    $entry = new DebugbarEntry();

    $entry->setCached();
    expect($entry->isCached())->toBeTrue();

    $entry->setCached(false);
    expect($entry->isCached())->toBeFalse();
});

it('has correct toArray output when cached', function () {
    date_default_timezone_set('Europe/Riga');

    $entry = new DebugbarEntry();

    $method = app(TestMethodWithoutCache::class);

    $payload = app(TestPayload::class);
    $payload->setParameter('payload data');

    $callStats = new CallStats();
    $callStats->setMicrotimeStart(1684295251);

    $entry
        ->setMethod($method)
        ->setPayload($payload)
        ->setCallStats($callStats)
        ->setCached();

    expect($entry->toArray())->toBe([
        'isCached' => true,
        'method' => 'TestMethodWithoutCache',
        'service' => TestService::class,
        'request' => '',
        'executionTime' => '',
        'startTime' => '2023-05-17 06:47:31'
    ]);
});

it('has correct toArray output when not cached', function () {
    date_default_timezone_set('Europe/Riga');

    $entry = new DebugbarEntry();

    $method = app(TestMethodWithoutCache::class);

    $payload = app(TestPayload::class);
    $payload->setParameter('payload data');

    $callStats = new CallStats();
    $callStats->setMicrotimeStart(1684295251.5);
    $callStats->setMicrotimeFinish(1684295251.75);
    $callStats->setRequestString('request string');

    $entry
        ->setMethod($method)
        ->setPayload($payload)
        ->setCallStats($callStats);

    expect($entry->toArray())->toBe([
        'isCached' => false,
        'method' => 'TestMethodWithoutCache',
        'service' => TestService::class,
        'request' => 'request string',
        'executionTime' => 0.25,
        'startTime' => '2023-05-17 06:47:31'
    ]);
});
