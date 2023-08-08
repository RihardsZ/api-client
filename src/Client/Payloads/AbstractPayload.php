<?php

namespace CubeSystems\ApiClient\Client\Payloads;

use CubeSystems\ApiClient\Client\Contracts\Payload;

abstract class AbstractPayload implements Payload
{
    protected const STRING_BOOL_TRUE = 'Y';
    protected const STRING_BOOL_FALSE = 'N';

    public function isCacheRetrievalAllowed(): bool
    {
        return true;
    }
}
