<?php

namespace CubeSystems\ApiClient\Events;

use CubeSystems\ApiClient\Client\Contracts\Method;
use CubeSystems\ApiClient\Client\Contracts\Payload;
use CubeSystems\ApiClient\Client\Contracts\Response;
use CubeSystems\ApiClient\Client\Stats\CallStats;
use Illuminate\Foundation\Events\Dispatchable;

class ResponseRetrievedFromCache extends ApiDebugEvent
{
}
