<?php

namespace Kontuak\Adapters\InMemory\Movement;

use Kontuak\Adapters\InMemory\Movement;
use Kontuak\Movement\Factory as FactoryInterface;
use Kontuak\Movement\Id;

class Factory implements FactoryInterface
{

    /**
     * @param Id $movementId
     * @param $amount
     * @param $concept
     * @param \DateTime $date
     * @param \DateTime $created
     * @return \Kontuak\Movement
     */
    public function make(Id $movementId, $amount, $concept, \DateTime $date, \DateTime $created)
    {
        return new Movement($movementId, $amount, $concept, $date, $created);
    }
}