<?php

use CodeDredd\Soap\SoapClient;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Services\TestService;

beforeEach(function () {
    app()->singleton(TestEndpoint::class, function () {
        return new TestEndpoint('https://www.w3schools.com');
    });
});

it('has working getters', function () {
    $service = app(TestService::class);

    expect($service->getClient())->toBeInstanceOf(SoapClient::class)
        ->and($service->getHeaders())
            ->toBeCollection()
            ->toHaveCount(0)
        ->and($service->getUrl())->toBe('https://www.w3schools.com/xml/tempconvert.asmx?wsdl');
});
