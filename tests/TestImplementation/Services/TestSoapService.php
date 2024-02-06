<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Services;

use CodeDredd\Soap\Facades\Soap;
use CubeSystems\ApiClient\Client\ApiClient;
use CubeSystems\ApiClient\Client\Services\AbstractSoapService;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestSoapEndpoint;
use Illuminate\Support\Collection;

class TestSoapService extends AbstractSoapService
{
    protected const SERVICE_PATH = 'xml/tempconvert.asmx';

    public function __construct(
        TestSoapEndpoint $endpoint,
        Collection $headers,
        Collection $options,
        ApiClient $client
    ) {
        parent::__construct($endpoint, $headers, $options, $client);

        // this is done to overcome bug in CodeDredd package where
        // faking works only with package's ApiClient class
        // i.e. it doesn't work with ApiClient extensions
        $this->client = Soap::baseWsdl($this->getUrl())
            ->withOptions([
                'trace' => true
            ]);
    }
}
