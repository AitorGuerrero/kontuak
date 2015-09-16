<?php

namespace Kontuak\Implementation\Movement\Source;

use Kontuak\Movement;
use Kontuak\Implementation\Movement\Collection;

class InMemory implements Movement\Source
{
    /**
     * @var Movement[]
     */
    private $movements = [];
    /**
     * @return Collection\InMemory
     */
    public function collection()
    {
        return new Collection\InMemory($this);
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