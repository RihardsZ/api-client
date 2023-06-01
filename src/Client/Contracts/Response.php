<?php

namespace CubeSystems\ApiClient\Client\Contracts;

use CubeSystems\ApiClient\Client\Responses\ResponseStatusInfo;

interface Response
{
    public function getRawData(): array;

    public function getStatusInfo(): ResponseStatusInfo;
}
