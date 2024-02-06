<?php

declare(strict_types=1);

use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestRestEndpoint;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestSoapEndpoint;

it('constructs the correct wsdl url for soap endpoint', function () {
    $endpoint = new TestSoapEndpoint('https://www.w3schools.com');

    expect($endpoint->getAbsoluteUrl('xml/tempconvert.asmx'))
        ->toBe('https://www.w3schools.com/xml/tempconvert.asmx?wsdl');
});

it('constructs the correct url for rest endpoint', function () {
    $endpoint = new TestRestEndpoint('https://www.w3schools.com');

    expect($endpoint->getAbsoluteUrl('path/to/resource'))
        ->toBe('https://www.w3schools.com/path/to/resource');
});
