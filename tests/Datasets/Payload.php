<?php

declare(strict_types=1);

use CubeSystems\ApiClient\Client\Cache\CachePrefix;

dataset('payload-hierarchy-key', [
    'no prefix, not using' => [
        'prefix' => null,
        'usingHierarchy' => false,
        'expectedHierarchyKey' => '',
    ],
    'prefix, not using' => [
        'prefix' => new CachePrefix('prefix'),
        'usingHierarchy' => false,
        'expectedHierarchyKey' => '',
    ],
    'no prefix, using' => [
        'prefix' => null,
        'usingHierarchy' => true,
        'expectedHierarchyKey' => '',
    ],
    'prefix, using' => [
        'prefix' => new CachePrefix('prefix'),
        'usingHierarchy' => true,
        'expectedHierarchyKey' => 'prefix:CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload',
    ],
]);

dataset('payload-cache-key', [
    'no prefix, no parameter' => [
        'prefix' => null,
        'parameter' => null,
        'expectedCacheKey' => md5(':CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload:'),
    ],
    'prefix, no parameter' => [
        'prefix' => 'prefix',
        'parameter' => null,
        'expectedCacheKey' => md5('prefix:CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload:'),
    ],
    'no prefix, empty parameter' => [
        'prefix' => null,
        'parameter' => '',
        'expectedCacheKey' => md5(':CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload:'),
    ],
    'prefix, empty parameter' => [
        'prefix' => 'prefix',
        'parameter' => '',
        'expectedCacheKey' => md5('prefix:CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload:'),
    ],
    'no prefix, nonempty parameter' => [
        'prefix' => null,
        'parameter' => 'thing',
        'expectedCacheKey' => md5(':CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload:thing'),
    ],
    'prefix, nonempty parameter' => [
        'prefix' => 'prefix',
        'parameter' => 'thing',
        'expectedCacheKey' => md5('prefix:CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload:thing'),
    ],
]);
