<?php

declare(strict_types=1);

use CodeDredd\Soap\Facades\Soap;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestRestEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestSoapEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap\TestMethodWithoutCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap\TestMethodWithPlug;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\Soap\TestMethodWithRequestCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\Rest\TestMethodWithoutCache as RestMethodWithoutCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload;
use CubeSystems\ApiClient\Tests\TestImplementation\Plugs\TestPlug;
use CubeSystems\ApiClient\Tests\TestImplementation\Plugs\TestPlugManager;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestEntity;
use CubeSystems\ApiClient\Tests\TestImplementation\Responses\TestResponse;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Soap::fake(function ($request) {
        return match ($request->offsetGet('parameter')) {
            'error' => Soap::response([
                'status' => [
                    'code' => 'E',
                    'message' => 'Error',
                ],
            ]),

            'technical error' => Soap::response(
                [
                    'status' => [
                        'code' => 'T',
                        'message' => 'Technical error',
                    ],
                ],
                500
            ),

            'empty' => Soap::response(null, 500),

            default => Soap::response([
                'name' => 'Test tester',
                'age' => 21,
                'status' => [
                    'code' => 'S',
                    'message' => 'Success',
                ]
            ]),
        };
    });

    app()->singleton(TestSoapEndpoint::class, function () {
        return new TestSoapEndpoint('https://www.w3schools.com');
    });

    Http::fake(function (HttpRequest $request) {
        return match ($request->offsetGet('parameter')) {
            'error' => Http::response([
                'status' => 'E',
            ]),

            'technical error' => Http::response(
                [
                    'status' => 'T',
                ],
                500
            ),

            default => Http::response([
                'name' => 'Test tester',
                'age' => 21,
                'status' => 'S',
            ]),
        };
    });

    app()->singleton(TestRestEndpoint::class, function () {
        return new TestRestEndpoint('https://www.w3schools.com');
    });

    Event::fake();
});

it('makes meaningful response when soap calling is successful', function () {
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

it('makes meaningful response when calling rest is successful', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('test');

    /** @var RestMethodWithoutCache $method */
    $method = app(RestMethodWithoutCache::class);

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

it('makes meaningful response when retrieving successful soap call response from cache', function () {
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

it('makes meaningful error response when retrieving empty soap call response from remote', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('empty');

    /** @var TestMethodWithRequestCache $method */
    $method = app(TestMethodWithRequestCache::class);

    /** @var TestResponse $response */
    $response = $method->call($payload);

    expect($response)->toBeInstanceOf(TestResponse::class)
        ->and($response->getStatusInfo())
        ->isSuccess()->toBeFalse()
        ->isTechnicalError()->toBeTrue();
});

it('retrieves response from plug if one exists, proceeds to call soap method otherwise', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);

    /** @var TestPlugManager $plugManager */
    $plugManager = app(TestPlugManager::class, [
        'plugs' => [
            'TestMethodWithPlug' => new TestPlug([
                'owo' => 'uwu',
                'name' => 'Test',
                'status' => [
                    'code' => 'S',
                    'message' => 'Success',
                ],
            ]),
        ],
    ]);

    /** @var TestMethodWithPlug $methodWithPlug */
    $methodWithPlug = app(TestMethodWithPlug::class, [
        'plugManager' => $plugManager,
    ]);

    /** @var TestMethodWithoutCache $methodWithoutPlug */
    $methodWithoutPlug = app(TestMethodWithoutCache::class);

    expect($methodWithPlug->call($payload)->getRawData())->toHaveKey('owo');
    expect($methodWithoutPlug->call($payload)->getRawData())->not()->toHaveKey('owo');
});

it('throws exception on accessing entity when having unsuccessful soap call response', function () {
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

