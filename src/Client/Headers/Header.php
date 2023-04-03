<?php

namespace CubeSystems\SoapClient\Client\Headers;

class Header
{
    public function __construct(
        private string $namespace,
        private string $name,
        private string $value
    ) {}

    public function hasNamespace(): bool
    {
        return (bool) $this->namespace;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
