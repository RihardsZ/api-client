<?php

namespace CubeSystems\ApiClient\Client\Contracts;

use Illuminate\Support\Collection;

interface Plug
{
    public function getResponse(): array;

    public function getStatusCode(): int;

    public function getResponseHeaders(): array;
}
