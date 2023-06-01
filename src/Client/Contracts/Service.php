<?php

namespace CubeSystems\ApiClient\Client\Contracts;

use CubeSystems\ApiClient\Client\ApiClient;
use Illuminate\Support\Collection;

interface Service
{
    public function getClient(): ApiClient;

    public function getHeaders(): Collection;

    public function getUrl(): string;
}
