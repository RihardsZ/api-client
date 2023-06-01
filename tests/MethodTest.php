<?php

use CodeDredd\Soap\Facades\Soap;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\TestMethodWithoutCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\TestMethodWithRequestCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestEntity;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestResponse;

use Illuminate\Support\Facades\Event;

beforeAll(function () {
//    Event::fake();
});

beforeEach(function () {
    Soap::fake(function ($request) {
        return match ($request->offsetGet('parameter')) {
            'error' => Soap::response([
                'status' => 'E',
            ]),

            'technical error' => Soap::response(
                [
                    'status' => 'T',
                ],
                500
            ),

            default => Soap::response([
                'name' => 'Test tester',
                'age' => 21,
                'status' => 'S',
            ]),
        };
    });

    app()->singleton(TestEndpoint::class, function () {
        return new TestEndpoint('https://www.w3schools.com');
    });

    Event::fake();
});

it('makes meaningful response when calling is successful', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('test');

    /** @var TestMethodWithoutCache $method */
    $method = app(TestMethodWithoutCache::class);

    /** @var TestResponse $response */
    $response = $method->call($payload);

    expect($response)->toBeInstanceOf(TestResponse::class)
        ->and($response->getStatusInfo())
            ->isSuccess()->toBeTrue()
        ->and($response->getEntity())
            ->toBeInstanceOf(TestEntity::class)
            ->getName()->toBe('Test tester')
            ->getAge()->toBe(21);
});

it('makes meaningful response when retrieving successful response from cache', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('test');

    /** @var TestMethodWithRequestCache $method */
    $method = app(TestMethodWithRequestCache::class);

    // this call saves response to cache
    $method->call($payload);

    // this call retrieves response from cache
    /** @var TestResponse $response */
    $response = $method->call($payload);

    expect($response)->toBeInstanceOf(TestResponse::class)
        ->and($response->getStatusInfo())
            ->isSuccess()->toBeTrue()
        ->and($response->getEntity())
            ->toBeInstanceOf(TestEntity::class)
            ->getName()->toBe('Test tester')
            ->getAge()->toBe(21);
});

it('throws exception on accessing entity when having unsuccessful response', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('error');

    /** @var TestMethodWithoutCache $method */
    $method = app(TestMethodWithoutCache::class);

    /** @var TestResponse $response */
    $response = $method->call($payload);

    expect($response->getStatusInfo()->isError())->toBeTrue();

    // attempt to use uninitialized property
    $response->getEntity();

})->throws(Error::class, 'Typed property CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestResponse::$entity must not be accessed before initialization');

afterEach(function () {
//    app()->forgetInstances();
});
