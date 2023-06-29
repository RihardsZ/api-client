<?php

use CubeSystems\ApiClient\Client\Responses\ResponseStatusInfo;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestEntity;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestResponse;


it('gets what it sets', function () {
    /** @var TestResponse $response */
    $response = app(TestResponse::class);

    /** @var TestEntity $entity */
    $entity = app(TestEntity::class);

    /** @var ResponseStatusInfo $statusInfo */
    $statusInfo = app(ResponseStatusInfo::class);

    $response
        ->setEntity($entity)
        ->setRawData(['test-key' => 'test-value'])
        ->setStatusInfo($statusInfo);

    expect($response->getEntity())->toBe($entity)
        ->and($response->getRawData())->toBe(['test-key' => 'test-value'])
        ->and($response->getStatusInfo())->toBe($statusInfo);
});
