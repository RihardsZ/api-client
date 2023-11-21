<?php

use CubeSystems\ApiClient\Client\Cache\CachePrefix;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\MinimalisticTestPayload;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload;

it('returns correct cache hierarchy keys', function (
    ?CachePrefix $cachePrefix,
    bool $isUsingCacheHierarchy,
    string $expectedCacheHierarchyKey
) {
    $payload = new TestPayload($cachePrefix);
    $payload->setUseCacheHierarchy($isUsingCacheHierarchy);
    $payload->setParameter('test');

    $key = $payload->getCacheHierarchyKey();

    expect($key)->toBe($expectedCacheHierarchyKey);
})->with('payload-hierarchy-key');

it('returns correct cache keys', function (
    ?string $prefixString,
    ?string $parameter,
    string $expectedCacheHierarchyKey
) {
    $prefix = $prefixString !== null ? new CachePrefix($prefixString) : null;

    $payload = new TestPayload($prefix);

    if ($parameter !== null) {
        $payload->setParameter($parameter);
    }

    $key = $payload->getCacheKey();

    expect($key)->toBe($expectedCacheHierarchyKey);
})->with('payload-cache-key');

it('gets what it sets', function () {
    $payload = new TestPayload();

    expect($payload->isUsingCacheHierarchy())->toBeFalse();

    $payload->setUseCacheHierarchy(false);
    expect($payload->isUsingCacheHierarchy())->toBeFalse();

    $payload->setUseCacheHierarchy(true);
    expect($payload->isUsingCacheHierarchy())->toBeTrue();
});

it('has expected defaults inherited from AbstractPayload', function () {
    $minimalisticPayload = new MinimalisticTestPayload();
    expect($minimalisticPayload->isUsingCacheHierarchy())->toBeFalse()
        ->and($minimalisticPayload->isCacheRetrievalAllowed())->toBeTrue();
});
