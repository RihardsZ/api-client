<?php

namespace CubeSystems\SoapClient\Facades;

use CodeDredd\Soap\Facades\Soap as BaseSoap;
use CubeSystems\SoapClient\Client\SoapClient;

/**
 * @see SoapClient
 * @method static SoapClient withSoapHeaders()
 * @method static SoapClient baseWsdl(string $wsdl)
 * @method static SoapClient stub(callable $callback)
 * @method static SoapClient buildClient(string $setup = '')
 * @method static SoapClient byConfig(string $setup = '')
 * @method static SoapClient withOptions(array $options)
 * @method static SoapClient withHeaders(array $options)
 * @method static SoapClient withGuzzleClientOptions(array $options)
 * @method static SoapClient withWsse(array $config)
 * @method static SoapClient withWsa()
 * @method static SoapClient withRemoveEmptyNodes()
 * @method static SoapClient withBasicAuth(string $username, string $password)
 * @method static SoapClient withCisDHLAuth($user, ?string $signature = null)
 */
class Soap extends BaseSoap
{
    protected static function getFacadeAccessor(): string
    {
        return SoapClient::class;
    }
}
