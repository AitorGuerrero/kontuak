<?php

namespace Kontuak\Implementation\InMemory\Movement;

use Kontuak\Movement;

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
}