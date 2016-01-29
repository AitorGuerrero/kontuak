<?php

namespace Kontuak\Adapters\InMemory;

use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;

class PeriodicalMovement extends \Kontuak\PeriodicalMovement
{
    public function __construct(
        Id $id,
        $amount,
        $concept,
        Period $period
    ) {
        parent::__construct($id, $amount, $concept, $period);
    }
}