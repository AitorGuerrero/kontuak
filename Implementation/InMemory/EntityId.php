<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\EntityId as Base;

class EntityId implements Base
{
    private $intId;

    public function __construct($intId)
    {
        $this->intId = $intId;
    }

    public function serialize()
    {
        return $this->intId;
    }
}