<?php

namespace CubeSystems\SoapClient\Client\Payloads;

use CubeSystems\SoapClient\Client\Contracts\Payload;

abstract class AbstractPayload implements Payload
{
    protected const STRING_BOOL_TRUE = 'Y';
    protected const STRING_BOOL_FALSE = 'N';
}
