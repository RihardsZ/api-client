<?php

declare(strict_types=1);

use CubeSystems\ApiClient\Client\ApiClient;
use CubeSystems\ApiClient\Client\Headers\Header;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestRestEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestSoapEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestRestService;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestSoapService;

beforeEach(function () {
    app()->singleton(TestSoapEndpoint::class, function () {
        return new TestSoapEndpoint('https://www.w3schools.com');
    });
});

it('checks soap service has working getters', function () {
    $endpoint = new TestSoapEndpoint('https://www.w3schools.com');
    $headers = collect([
        new Header('', 'Content-Type', 'text/xml; charset=utf-8')
    ]);
    $options = collect([
        'trace' => true,
        'verify' => false,
    ]);
    $apiClient = new ApiClient();
    $service = new TestSoapService($endpoint, $headers, $options, $apiClient);

    expect($service->getClient())
        ->toBeInstanceOf(ApiClient::class)
        ->and($service->getHeaders())
            ->toBeCollection()
            ->toHaveCount(1)
            ->toBe($headers)
        ->and($service->getOptions())
            ->toBeCollection()
            ->toHaveCount(2)
            ->toBe($options)
        ->and($service->getUrl())
            ->toBe('https://www.w3schools.com/xml/tempconvert.asmx?wsdl');
});

it('checks rest service has working getters', function () {
    $endpoint = new TestRestEndpoint('https://www.test-rest-api.com');

    $headers = collect([
        'Content-Type' => 'application/json',
    ]);

    $options = collect([
        'trace' => true,
        'verify' => false,
    ]);

    $service = new TestRestService($endpoint, $headers, $options);

    expect($service->getHeaders())
        ->toBeCollection()
        ->toHaveCount(1)
        ->toBe($headers)
        ->and($service->getOptions())
        ->toBeCollection()
        ->toHaveCount(2)
        ->toBe($options)
        ->and($service->getUrl())
        ->toBe('https://www.test-rest-api.com/test-rest-service');
});
