<?php

namespace Elektrum\SoapClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Elektrum\SoapClient\SoapClient
 */
class SoapClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Elektrum\SoapClient\SoapClient::class;
    }
}
