<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;

class PeriodicalMovement extends \Kontuak\PeriodicalMovement
{
    public function __construct(
        Id $id,
        $amount,
        $concept,
        \DateTime $starts,
        Period $period
    ) {
        parent::__construct($id, $amount, $concept, $starts, $period);
    }
}