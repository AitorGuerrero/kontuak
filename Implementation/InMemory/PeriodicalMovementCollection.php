<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Collection;

class PeriodicalMovementCollection implements Collection
{
    private $identifierCounter = 1;
    /** @var []PeriodicalMovement */
    private $collection = [];

    public function add(PeriodicalMovement $periodicalMovement)
    {
        $id = new EntityId($this->identifierCounter++);
        $periodicalMovement->identify($id);
        $this->collection[$id->serialize()] = $periodicalMovement;
    }

    /**
     * @param \Kontuak\EntityId $entityId
     * @return PeriodicalMovement
     */
    public function find(\Kontuak\EntityId $entityId)
    {
        return $this->collection[$entityId->serialize()];
    }

    public function all()
    {
        return $this->collection;
    }
}