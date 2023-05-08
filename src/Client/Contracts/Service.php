<?php

namespace CubeSystems\SoapClient\Client\Contracts;

use CubeSystems\SoapClient\Client\SoapClient;
use Illuminate\Support\Collection;

interface Service
{
    public function getClient(): SoapClient;

    public function getHeaders(): Collection;

    public function getUrl(): string;
}
