<?php

namespace Kontuak\Implementation\InMemory\Movement;

use Kontuak\Movement;
use Kontuak\Movement\Source as BaseMovementsSource;

class Source implements BaseMovementsSource
{
    /**
     * @var Movement[]
     */
    private $movements = [];
    /**
     * @return Collection
     */
    public function collection()
    {
        return new Collection($this);
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