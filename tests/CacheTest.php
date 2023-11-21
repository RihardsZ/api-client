<?php

use CodeDredd\Soap\Facades\Soap;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromCache;
use CubeSystems\ApiClient\Events\ApiCalled;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\TestMethodWithoutCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\TestMethodWithRequestCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Methods\TestMethodWithTimeIntervalCache;
use CubeSystems\ApiClient\Tests\TestImplementation\Payloads\TestPayload;
use Illuminate\Support\Facades\Event;

use function Spatie\PestPluginTestTime\testTime;

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

it('always calls service when using NeverCache strategy', function (bool $useHierarchy) {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('test');
    $payload->setUseCacheHierarchy($useHierarchy);

    /** @var TestMethodWithoutCache $method */
    $method = app(TestMethodWithoutCache::class);

    $method->call($payload);
    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload);
    Event::assertDispatchedTimes(ApiCalled::class, 2);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);
})->with([
    true, // sets hierarchy
    false, // does not set hierarchy
]);

it('calls service only once for given payload when using RequestCache', function () {
    /** @var TestPayload $payload1 */
    $payload1 = app(TestPayload::class);
    $payload1->setParameter('test');

    /** @var TestMethodWithRequestCache $method */
    $method = app(TestMethodWithRequestCache::class);

    $method->call($payload1);
    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload1);
    // no additional ServiceCalled events
    Event::assertDispatchedTimes(ApiCalled::class, 1);
    // one additional ResponseRetrievedFromCache event
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 1);
});

it('calls service for given payload after setting cache not to be used when using RequestCache', function () {
    /** @var TestPayload $payload1 */
    $payload1 = app(TestPayload::class);
    $payload1->setParameter('test');

    /** @var TestMethodWithRequestCache $method */
    $method = app(TestMethodWithRequestCache::class);

    $method->call($payload1);
    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload1);
    // no additional ServiceCalled events
    Event::assertDispatchedTimes(ApiCalled::class, 1);
    // one additional ResponseRetrievedFromCache event
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 1);

    $payload1->setUseCache(false);
    $method->call($payload1);
    // one additional ServiceCalled event
    Event::assertDispatchedTimes(ApiCalled::class, 2);
    // no additional ResponseRetrievedFromCache events
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 1);
});

it('calls service when given different payload when using RequestCache', function () {
    /** @var TestPayload $payload1 */
    $payload1 = app(TestPayload::class);
    $payload1->setParameter('test');

    /** @var TestPayload $payload2 */
    $payload2 = app(TestPayload::class);
    $payload2->setParameter('something else');

    /** @var TestMethodWithRequestCache $method */
    $method = app(TestMethodWithRequestCache::class);

    $method->call($payload1);

    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload2);
    // one additional ServiceCalled event
    Event::assertDispatchedTimes(ApiCalled::class, 2);
    // no additional ResponseRetrievedFromCache events
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload2);
    // no additional ServiceCalled events
    Event::assertDispatchedTimes(ApiCalled::class, 2);
    // one additional ResponseRetrievedFromCache event
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 1);
});

it('does not call service when using TimeIntervalCache and time\'s not up', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('test');

    /** @var TestMethodWithTimeIntervalCache $method */
    $method = app(TestMethodWithTimeIntervalCache::class);
    $method->call($payload);

    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload);
    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 1);
});

it('calls service when response has been removed from cache', function () {
    /** @var TestPayload $payload1 */
    $payload1 = app(TestPayload::class);
    $payload1->setParameter('test1');
    $payload1->setUseCacheHierarchy(true);

    /** @var TestPayload $payload2 */
    $payload2 = app(TestPayload::class);
    $payload2->setParameter('test2');
    $payload2->setUseCacheHierarchy(true);

    /** @var TestMethodWithTimeIntervalCache $method */
    $method = app(TestMethodWithTimeIntervalCache::class);
    $method->call($payload1);
    $method->call($payload2);

    Event::assertDispatchedTimes(ApiCalled::class, 2);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload1);
    $method->call($payload2);
    Event::assertDispatchedTimes(ApiCalled::class, 2);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 2);

    $method->removeFromCache($payload1);

    $method->call($payload1);
    $method->call($payload2);
    Event::assertDispatchedTimes(ApiCalled::class, 3);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 3);
});

it('calls service when hierarchy has been forgotten', function () {
    /** @var TestPayload $payload1 */
    $payload1 = app(TestPayload::class);
    $payload1->setParameter('test1');
    $payload1->setUseCacheHierarchy(true);

    /** @var TestPayload $payload2 */
    $payload2 = app(TestPayload::class);
    $payload2->setParameter('test2');
    $payload2->setUseCacheHierarchy(true);

    /** @var TestMethodWithTimeIntervalCache $method */
    $method = app(TestMethodWithTimeIntervalCache::class);
    $method->call($payload1);
    $method->call($payload2);

    Event::assertDispatchedTimes(ApiCalled::class, 2);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    $method->call($payload1);
    $method->call($payload2);
    Event::assertDispatchedTimes(ApiCalled::class, 2);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 2);

    $method->removeHierarchyFromCache($payload1);

    $method->call($payload1);
    $method->call($payload2);
    Event::assertDispatchedTimes(ApiCalled::class, 4);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 2);
});

it('does call service when using TimeIntervalCache and time is up', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('test');

    /** @var TestMethodWithTimeIntervalCache $method */
    $method = app(TestMethodWithTimeIntervalCache::class);
    $method->call($payload);

    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);

    // add 20 minutes, i.e. more than caching time which for this method is 10 minutes
    testTime()->addSeconds(2 * 10 * 60);

    $method->call($payload);
    Event::assertDispatchedTimes(ApiCalled::class, 2);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);
});

it('does cache for error responses', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('error');

    /** @var TestMethodWithRequestCache $method */
    $method = app(TestMethodWithRequestCache::class);
    $method->call($payload);
    $method->call($payload);

    Event::assertDispatchedTimes(ApiCalled::class, 1);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 1);
});

it('does not cache anything for technical error responses', function () {
    /** @var TestPayload $payload */
    $payload = app(TestPayload::class);
    $payload->setParameter('technical error');

    /** @var TestMethodWithRequestCache $method */
    $method = app(TestMethodWithRequestCache::class);
    $method->call($payload);
    $method->call($payload);

    Event::assertDispatchedTimes(ApiCalled::class, 2);
    Event::assertDispatchedTimes(ResponseRetrievedFromCache::class, 0);
});
