<?php

namespace Kontuak\Implementation\InMemory\PeriodicalMovement;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Source as SourceInterface;

class Source implements SourceInterface
{
    /** @var PeriodicalMovement[] */
    private $collection = [];

    /**
     * @return Collection
     */
    public function collection()
    {
        return new Collection($this);
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