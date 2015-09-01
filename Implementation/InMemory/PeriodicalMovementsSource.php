<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovementsCollection;
use Kontuak\PeriodicalMovementsSource as BasePeriodicalMovementsSource;

class PeriodicalMovementsSource implements BasePeriodicalMovementsSource
{
    /** @var PeriodicalMovement[] */
    private $collection;

    /**
     * @return PeriodicalMovementsCollection
     */
    public function collection()
    {
        return new PeriodicalMovementCollection($this);
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
}