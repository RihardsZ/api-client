<?php

namespace CubeSystems\ApiClient\Facades;

use CodeDredd\Soap\Facades\Soap as BaseSoap;
use CubeSystems\ApiClient\Client\ApiClient;

/**
 * @see ApiClient
 * @method static ApiClient withSoapHeaders()
 * @method static ApiClient baseWsdl(string $wsdl)
 * @method static ApiClient stub(callable $callback)
 * @method static ApiClient buildClient(string $setup = '')
 * @method static ApiClient byConfig(string $setup = '')
 * @method static ApiClient withOptions(array $options)
 * @method static ApiClient withHeaders(array $options)
 * @method static ApiClient withGuzzleClientOptions(array $options)
 * @method static ApiClient withWsse(array $config)
 * @method static ApiClient withWsa()
 * @method static ApiClient withRemoveEmptyNodes()
 * @method static ApiClient withBasicAuth(string $username, string $password)
 * @method static ApiClient withCisDHLAuth($user, ?string $signature = null)
 */
class Api extends BaseSoap
{
    protected static function getFacadeAccessor(): string
    {
        return ApiClient::class;
    }
}
