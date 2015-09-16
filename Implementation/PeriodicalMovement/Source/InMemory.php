<?php

namespace Kontuak\Implementation\PeriodicalMovement\Source;

use Kontuak\PeriodicalMovement;
use Kontuak\Implementation\PeriodicalMovement\Collection;

class InMemory implements PeriodicalMovement\Source
{
    /** @var PeriodicalMovement[] */
    private $collection = [];

    /**
     * @return Collection\InMemory
     */
    public function collection()
    {
        return new Collection\InMemory($this);
    }

    public function add(PeriodicalMovement $movement)
    {
        $this->collection[$movement->id()->serialize()] = $movement;
    }

    /**
     * @return \Kontuak\PeriodicalMovement[]
     */
    public function toArray()
    {
        return $this->collection;
    }

    public function byId($periodicalMovementId)
    {
        return $this->collection[$periodicalMovementId];
    }
}