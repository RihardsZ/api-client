<?php

use CubeSystems\ApiClient\Client\Stats\CallStats;
use CubeSystems\ApiClient\Debugbar\ApiCollector;
use CubeSystems\ApiClient\Debugbar\DebugbarEntry;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestSoapEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap\TestMethodWithoutCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload;

beforeEach(function () {
    app()->singleton(TestSoapEndpoint::class, function () {
        return new TestSoapEndpoint('https://www.w3schools.com');
    });
});

it('is empty at the start', function () {
    $collector = new ApiCollector();

    expect($collector->collect()['entries'])->toBe([])
        ->and($collector->collect()['badge'])->toBe('0 | 0');
});

it('correctly fills with entries', function () {
    $collector = new ApiCollector();

    $nonCachedEntry = new DebugbarEntry();

    $method = app(TestMethodWithoutCache::class);

    $payload = app(TestPayload::class);
    $payload->setParameter('payload data');

    $callStats = new CallStats();
    $callStats->setMicrotimeStart(1684295251.5);
    $callStats->setMicrotimeFinish(1684295251.75);
    $callStats->setRequestString('request string');

    $nonCachedEntry
        ->setMethod($method)
        ->setPayload($payload)
        ->setCallStats($callStats);

    $collector->addEntry($nonCachedEntry);

    expect($collector->collect()['entries'])->toBe([$nonCachedEntry->toArray()])
        ->and($collector->collect()['badge'])->toBe('0 | 1');

    $cachedEntry = new DebugbarEntry();
    $cachedEntry
        ->setMethod($method)
        ->setPayload($payload)
        ->setCallStats($callStats)
        ->setCached();

    $collector->addEntry($cachedEntry);

    expect($collector->collect()['entries'])->toBe([
        $nonCachedEntry->toArray(),
        $cachedEntry->toArray()
    ])->and($collector->collect()['badge'])->toBe('1 | 1');

    $collector->addEntry($cachedEntry);
    $collector->addEntry($cachedEntry);
    $collector->addEntry($cachedEntry);

    $collector->addEntry($nonCachedEntry);
    $collector->addEntry($nonCachedEntry);
    $collector->addEntry($nonCachedEntry);
    $collector->addEntry($nonCachedEntry);
    $collector->addEntry($nonCachedEntry);

    expect($collector->collect()['badge'])->toBe('4 | 6');
});
