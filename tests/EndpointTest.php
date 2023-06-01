<?php

use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestEndpoint;

it('constructs the correct wsdl url', function () {
    $endpoint = new TestEndpoint('https://www.w3schools.com');

    expect($endpoint->getWsdlUrl('xml/tempconvert.asmx'))
        ->toBe('https://www.w3schools.com/xml/tempconvert.asmx?wsdl');
});

