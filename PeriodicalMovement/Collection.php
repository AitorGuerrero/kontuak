<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

interface Collection
{

    /**
     * @param PeriodicalMovement\Id $id
     * @return PeriodicalMovement
     */
    public function find(PeriodicalMovement\Id $id);

    /**
     * @return PeriodicalMovement[]
     */
    public function all();
}