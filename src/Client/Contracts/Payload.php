<?php

namespace CubeSystems\SoapClient\Client\Contracts;

interface Payload
{
    public function toArray(): array;

    public function getCacheKey(): string;
}
