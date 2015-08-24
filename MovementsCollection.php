<?php

namespace Kontuak;

interface MovementsCollection
{
    /**
     * @param Movement $movement
     * @return mixed
     */
    public function add(Movement $movement);
    /**
     * @param EntityId $id
     * @return Movement
     */
    public function find(EntityId $id);
}