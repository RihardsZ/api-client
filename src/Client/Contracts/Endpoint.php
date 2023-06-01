<?php

namespace CubeSystems\ApiClient\Client\Contracts;

interface Endpoint
{
    public function getWsdlUrl(string $path): string;
}
