<?php

namespace CubeSystems\ApiClient\Client\Plugs;

use CubeSystems\ApiClient\Client\Contracts\Response;

interface PlugResponseInterface
{
    public function getResponse(): Response;

    public function getStatusCode(): int;
}
