<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Collection;
use Kontuak\PeriodicalMovementId;

class PeriodicalMovementCollection implements Collection
{
    /** @var []PeriodicalMovement */
    private $collection = [];

    public function add(PeriodicalMovement $periodicalMovement)
    {
        $this->collection[$periodicalMovement->id()->serialize()] = $periodicalMovement;
    }

    /**
     * @param PeriodicalMovementId $id
     * @return PeriodicalMovement
     */
    public function find(PeriodicalMovementId $id)
    {
        return $this->collection[$id->serialize()];
    }

    public function all()
    {
        return $this->collection;
    }
}