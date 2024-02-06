<?php

declare(strict_types=1);

use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Debugbar\DebugbarEntry;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestSoapEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap\TestMethodWithoutCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap\TestMethodWithPlug;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestSoapService;

beforeEach(function () {
    app()->singleton(TestSoapEndpoint::class, function () {
        return new TestSoapEndpoint('https://www.w3schools.com');
    });
});

it('says it\'s cached when it has been set cached', function () {
    $entry = new DebugbarEntry();

    $entry->setCached();
    expect($entry->isCached())->toBeTrue();

    $entry->setCached(false);
    expect($entry->isCached())->toBeFalse();
});

it('says it\'s from plug when it has been set from plug', function () {
    $entry = new DebugbarEntry();

    $entry->setFromPlug();
    expect($entry->isFromPlug())->toBeTrue();

    $entry->setFromPlug(false);
    expect($entry->isFromPlug())->toBeFalse();
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
        'isFromPlug' => false,
        'method' => 'TestMethodWithoutCache',
        'service' => TestSoapService::class,
        'request' => '',
        'executionTime' => '',
        'startTime' => '2023-05-17 06:47:31'
    ]);
});

it('has correct toArray output when is from plug', function () {
    date_default_timezone_set('Europe/Riga');

    $entry = new DebugbarEntry();

    $method = app(TestMethodWithPlug::class);

    $payload = app(TestPayload::class);
    $payload->setParameter('payload data');

    $callStats = new CallStats();
    $callStats->setRequestString('request string');
    $callStats->setMicrotimeStart(1684295251);

    $entry
        ->setMethod($method)
        ->setPayload($payload)
        ->setCallStats($callStats)
        ->setFromPlug();

    expect($entry->toArray())->toBe([
        'isCached' => false,
        'isFromPlug' => true,
        'method' => 'TestMethodWithPlug',
        'service' => TestSoapService::class,
        'request' => 'request string',
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
        ->setResponse(Mockery::mock(Response::class))
        ->setCallStats($callStats);

    expect($entry->toArray())->toBe([
        'isCached' => false,
        'isFromPlug' => false,
        'method' => 'TestMethodWithoutCache',
        'service' => TestSoapService::class,
        'request' => 'request string',
        'executionTime' => 0.25,
        'startTime' => '2023-05-17 06:47:31'
    ]);
});
