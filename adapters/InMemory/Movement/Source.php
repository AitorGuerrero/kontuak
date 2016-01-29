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
        return new Collection($this->movements
        );
    }

    public function add(Movement $movement)
    {
        $this->movements[$movement->id()->toString()] = $movement;
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
        unset($this->movements[$movement->id()->toString()]);
    }

    public function byId(Movement\Id $id)
    {
        return $this->movements[$id->toString()];
    }

    /**
     * @param Id $id
     * @return Movement
     * @throws \Kontuak\Exception\Source\EntityNotFound
     */
    public function get(Id $id)
    {
        if(!isset($this->movements[$id->toString()])) {
            throw new EntityNotFound();
        }

        return $this->movements[$id->toString()];
    }
}