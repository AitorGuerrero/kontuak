<?php

namespace Kontuak\Implementation\InMemory\PeriodicalMovement;

use Kontuak\Implementation\InMemory\PeriodicalMovement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Factory as FactoryInterface;
use Kontuak\PeriodicalMovement\Id;

class Factory implements FactoryInterface
{

    public function make(
        Id $id,
        $amount,
        $concept,
        \DateTime $starts,
        Period $period
    )
    {
        return new PeriodicalMovement($id, $amount, $concept, $starts, $period);
    }
}