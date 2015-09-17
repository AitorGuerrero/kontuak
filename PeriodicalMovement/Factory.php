<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\Period;
use Kontuak\PeriodicalMovement;

interface Factory 
{
    public function make(
        PeriodicalMovement\Id $id,
        $amount,
        $concept,
        \DateTime $starts,
        Period $period
    );
}