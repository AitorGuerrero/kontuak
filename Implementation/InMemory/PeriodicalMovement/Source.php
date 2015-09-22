<?php

namespace Kontuak\Implementation\InMemory\PeriodicalMovement;

use Kontuak\Exception\Source\DuplicatedId;
use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\Exception\Source\MalformedId;
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

    /**
     * @param PeriodicalMovement $movement
     * @throws DuplicatedId
     * @throws MalformedId
     */
    public function add(PeriodicalMovement $movement)
    {
        if(empty($movement->id()->serialize())) {
            throw new MalformedId();
        }
        if(isset($this->collection[$movement->id()->serialize()])) {
            throw new DuplicatedId();
        }
        $this->collection[$movement->id()->serialize()] = $movement;
    }

    /**
     * @return \Kontuak\PeriodicalMovement[]
     */
    public function toArray()
    {
        return $this->collection;
    }

    /**
     * @param PeriodicalMovement\Id $id
     * @throws EntityNotFound
     * @return PeriodicalMovement
     */
    public function get(PeriodicalMovement\Id $id)
    {
        if (!isset($this->collection[$id->serialize()])) {
            throw new EntityNotFound();
        }

        return $this->collection[$id->serialize()];
    }

    public function byId(PeriodicalMovement\Id $id)
    {
        return $this->collection[$id->serialize()];
    }
}