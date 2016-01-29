<?php

namespace kontuak\Adapters\InMemory\PeriodicalMovement;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Id;

class Collection implements PeriodicalMovement\Collection
{
    use \Kontuak\Adapters\InMemory\Collection;


    /**
     * Collection constructor.
     * @param PeriodicalMovement[] $arrayCollection
     */
    public function __construct($arrayCollection)
    {
        $this->collection = $arrayCollection;
    }

    /**
     * @param Id $id
     * @return Collection
     */
    public function byId(Id $id)
    {
        return new Collection(array_filter($this->collection, function(PeriodicalMovement $periodicalMovement) use ($id) {
            return $periodicalMovement->id() === $id;
        }));
    }
}