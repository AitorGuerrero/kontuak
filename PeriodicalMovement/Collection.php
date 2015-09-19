<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

interface Collection
{
    /**
     * @return PeriodicalMovement[]
     */
    public function all();
}