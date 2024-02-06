<?php

namespace CubeSystems\ApiClient\Client\Contracts;

use Illuminate\Support\Collection;

interface Service
{
    public function getHeaders(): Collection;

    public function getOptions(): Collection;

    public function getUrl(): string;
}
