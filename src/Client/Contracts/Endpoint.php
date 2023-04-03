<?php

namespace CubeSystems\SoapClient\Client\Contracts;

interface Endpoint
{
    public function getWsdlUrl(string $path): string;
}
