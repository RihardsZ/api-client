<?php

namespace CubeSystems\SoapClient\Client\Contracts;

use CubeSystems\SoapClient\Client\Responses\ResponseStatusInfo;

interface Response
{
    public function getRawData(): array;

    public function getStatusInfo(): ResponseStatusInfo;
}
