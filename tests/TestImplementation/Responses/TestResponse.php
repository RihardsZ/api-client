<?php

namespace CubeSystems\ApiClient\Tests\TestImplementation\Responses;

use CubeSystems\ApiClient\Client\Responses\AbstractResponse;

class TestResponse extends AbstractResponse
{
    private TestEntity $entity;

    public function getEntity(): TestEntity
    {
        return $this->entity;
    }

    public function setEntity(TestEntity $entity): TestResponse
    {
        $this->entity = $entity;

        return $this;
    }
}
