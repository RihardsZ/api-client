<?php

namespace CubeSystems\ApiClient\Client\Contracts;

interface Endpoint
{
    public function getAbsoluteUrl(string $path): string;
}
