<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\EntityId as Base;

class EntityId implements Base
{
    private $stringId;

    public function __construct($stringId)
    {
        $this->stringId = $stringId;
    }

    public function serialize()
    {
        return $this->stringId;
    }
}