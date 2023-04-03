<?php

namespace CubeSystems\SoapClient\Client\Contracts;

use CubeSystems\SoapClient\Client\SoapClient;

interface Service
{
    public function getClient(): SoapClient;
}
