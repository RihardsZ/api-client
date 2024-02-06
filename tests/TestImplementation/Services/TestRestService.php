<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Tests\TestImplementation\Services;

use CubeSystems\ApiClient\Client\Services\AbstractRestService;
use CubeSystems\ApiClient\Tests\TestImplementation\Endpoints\TestRestEndpoint;
use Illuminate\Support\Collection;

class TestRestService extends AbstractRestService
{
    protected const SERVICE_PATH = 'test-rest-service';

    public function __construct(
        TestRestEndpoint $endpoint,
        Collection $headers,
        Collection $options,
    ) {
        parent::__construct($endpoint, $headers, $options);
    }
}
