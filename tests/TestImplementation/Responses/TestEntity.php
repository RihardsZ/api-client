<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Responses;

class TestEntity
{
    private string $name;

    private int $age;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): TestEntity
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): TestEntity
    {
        $this->age = $age;

        return $this;
    }
}
