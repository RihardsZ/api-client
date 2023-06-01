<?php

namespace CubeSystems\ApiClient\Client\Headers;

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

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
