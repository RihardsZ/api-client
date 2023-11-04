<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Services;

use CodeDredd\Soap\Facades\Soap;
use CubeSystems\ApiClient\Client\ApiClient;
use CubeSystems\ApiClient\Client\Services\AbstractService;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestEndpoint;
use Illuminate\Support\Collection;

class TestService extends AbstractService
{
    protected const SERVICE_PATH = 'xml/tempconvert.asmx';

    public function __construct(
        TestEndpoint $endpoint,
        Collection $headers,
        ApiClient $client
    ) {
        parent::__construct($endpoint, $headers, $client);

        // this is done to overcome bug in CodeDredd package where
        // faking works only with package's ApiClient class
        // i.e. it doesn't work with ApiClient extensions
        $this->client = Soap::baseWsdl($this->getUrl())
            ->withOptions([
                'trace' => true
            ]);
    }
}
