<?php

namespace kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

interface Collection
{
    /**
     * @return PeriodicalMovement[]
     */
    public function getAll();
}