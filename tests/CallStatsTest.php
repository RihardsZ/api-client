<?php

use CubeSystems\ApiClient\Client\Stats\CallStats;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\RequestInterface;

it('gets what it sets',function () {
    $callStats = new CallStats();

    $callStats
        ->setRequestString('requestString')
        ->setRequestHeaders(collect(['requestHeaders']))
        ->setResponseString('responseString')
        ->setResponseHeaders(collect(['responseHeaders']))
        ->setMicrotimeStart(1.1)
        ->setMicrotimeFinish(2.2);

    $transferStats = new TransferStats(Mockery::mock(RequestInterface::class));
    $callStats->setTransferStats($transferStats);

    expect($callStats->getRequestString())->toBe('requestString')
        ->and($callStats->getRequestHeaders())->toEqual(collect(['requestHeaders']))
        ->and($callStats->getResponseString())->toBe('responseString')
        ->and($callStats->getResponseHeaders())->toEqual(collect(['responseHeaders']))
        ->and($callStats->getMicrotimeStart())->toBe(1.1)
        ->and($callStats->getMicrotimeFinish())->toBe(2.2)
        ->and($callStats->getTransferStats())->toBe($transferStats);
});

it('calculates the duration', function () {
    $callStats = new CallStats();

    $callStats
        ->setMicrotimeStart(1.1)
        ->setMicrotimeFinish(2.2);

    expect($callStats->getMicrotimeDifference())->toBe(1.1);

    $callStats
        ->setMicrotimeStart(10011223344.55)
        ->setMicrotimeFinish(93456789032.12);

    expect($callStats->getMicrotimeDifference())->toEqualWithDelta(83445565687.57, 0.001);
});
