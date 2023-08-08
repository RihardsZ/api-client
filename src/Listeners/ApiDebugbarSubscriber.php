<?php

namespace CubeSystems\ApiClient\Listeners;

use Barryvdh\Debugbar\Facades\Debugbar;
use CubeSystems\ApiClient\Debugbar\DebugbarEntry;
use CubeSystems\ApiClient\Events\ResponseRetrievedFromCache;
use CubeSystems\ApiClient\Events\ApiCalled;
use Illuminate\Events\Dispatcher;

class ApiDebugbarSubscriber
{
    public function subscribe(Dispatcher $eventsDispatcher): array
    {
        return [
            ApiCalled::class => 'handleServiceCall',
            ResponseRetrievedFromCache::class => 'handleRetrievalFromCache'
        ];
    }

    public function handleServiceCall(ApiCalled $event): void
    {
        $entry = new DebugbarEntry();

        $entry
            ->setCached(false)
            ->setMethod($event->getMethod())
            ->setPayload($event->getPayload())
            ->setResponse($event->getResponse())
            ->setCallStats($event->getStats());

        Debugbar::getCollector('api')->addEntry($entry);
    }

    public function handleRetrievalFromCache(ResponseRetrievedFromCache $event): void
    {
        $entry = new DebugbarEntry();

        $entry
            ->setCached(true)
            ->setMethod($event->getMethod())
            ->setPayload($event->getPayload())
            ->setResponse($event->getResponse())
            ->setCallStats($event->getCallStats());

        Debugbar::getCollector('api')->addEntry($entry);
    }
}
