<?php

namespace Kontuak\Adapters\InMemory\Movement;

use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\Movement;
use Kontuak\Movement\Id;

class Source implements Movement\Source
{
    /**
     * @var Movement[]
     */
    private $movements = [];
    /**
     * @return Collection
     */
    public function collection()
    {
        return new Collection($this);
    }

    public function add(Movement $movement)
    {
        $this->movements[$movement->id()->serialize()] = $movement;
    }

    /**
     * @return Movement[]
     */
    public function toArray()
    {
        return $this->movements;
    }

    /**
     * @param Movement $movement
     * @return void
     */
    public function remove(Movement $movement)
    {
        unset($this->movements[$movement->id()->serialize()]);
    }

    /**
     * @param Movement $movement
     */
    public function persist(Movement $movement)
    {
        $this->movements[$movement->id()->serialize()] = $movement;
    }

    public function byId(Movement\Id $id)
    {
        return $this->movements[$id->serialize()];
    }

    /**
     * @return Movement\Id
     */
    public function newId()
    {
        return new Movement\Id(uniqid('movement_id'));
    }

    /**
     * @param Id $id
     * @return Movement
     * @throws \Kontuak\Exception\Source\EntityNotFound
     */
    public function get(Id $id)
    {
        if(!isset($this->movements[$id->serialize()])) {
            throw new EntityNotFound();
        }

        return $this->movements[$id->serialize()];
    }
}