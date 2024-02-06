<?php

declare(strict_types=1);

namespace CubeSystems\ApiClient\Client\Services;

use CubeSystems\ApiClient\Client\Contracts\Endpoint;
use CubeSystems\ApiClient\Client\Contracts\Service;
use Illuminate\Support\Collection;

abstract class AbstractRestService implements Service
{
    protected const SERVICE_PATH = '';

    public function __construct(
        private Endpoint $endpoint,
        private Collection $headers,
        private Collection $options,
    ) {
    }

    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function getHeaders(): Collection
    {
        return $this->headers;
    }

    public function getUrl(): string
    {
        return $this->endpoint->getAbsoluteUrl($this->getPath());
    }

    private function getPath(): string
    {
        return static::SERVICE_PATH;
    }
}
