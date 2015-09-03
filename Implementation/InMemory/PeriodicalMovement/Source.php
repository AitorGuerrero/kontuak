<?php

namespace Kontuak\Implementation\InMemory\PeriodicalMovement;

use Kontuak\PeriodicalMovement;
use Kontuak\Implementation\InMemory;

class Source implements PeriodicalMovement\Source
{
    /** @var PeriodicalMovement[] */
    private $collection;

    /**
     * @return InMemory\PeriodicalMovement\Collection
     */
    public function collection()
    {
        return new InMemory\PeriodicalMovement\Collection($this);
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