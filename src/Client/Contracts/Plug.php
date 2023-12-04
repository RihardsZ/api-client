<?php

namespace CubeSystems\ApiClient\Client\Contracts;

interface Plug
{
    public function getResponse(): array;

    public function getStatusCode(): int;
}
