<?php

namespace kontuak\Adapters\InMemory\PeriodicalMovement;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Id;

class Collection implements PeriodicalMovement\Collection
{
    use \Kontuak\Adapters\InMemory\Collection;

    /** @var PeriodicalMovement\Source */
    private $source;

    public function __construct(Source $source)
    {
        $this->source = $source;
        $this->collection = $this->source->toArray();
    }

    /**
     * @param Id $id
     * @return Collection
     */
    public function byId(Id $id)
    {
        if (isset($this->collection[$id->toString()])) {
            $this->collection = [$this->collection[$id->toString()]];
        } else {
            $this->collection = [];
        }

        return $this;
    }
}