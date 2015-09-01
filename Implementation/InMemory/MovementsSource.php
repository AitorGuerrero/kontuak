<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Movement;
use Kontuak\MovementsSource as BaseMovementsSource;

class MovementsSource implements BaseMovementsSource
{
    /**
     * @var Movement[]
     */
    private $movements = [];
    /**
     * @return MovementsCollection
     */
    public function collection()
    {
        return new MovementsCollection($this);
    }

    public function add(Movement $movement)
    {
        $this->movements[$movement->id()->serialize()] = $movement;
    }

    /**
     * @return Movement[]
     */
    public function toArray()
    {
        return $this->movements;
    }
}