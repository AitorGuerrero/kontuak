<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\PeriodicalMovement;

class PeriodicalMovementCollection
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
     * @param EntityId $entityId
     * @return PeriodicalMovement
     */
    public function find(EntityId $entityId)
    {
        return $this->collection[$entityId->serialize()];
    }
}