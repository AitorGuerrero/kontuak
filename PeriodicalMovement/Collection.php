<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovementId;

interface Collection
{

    /**
     * @param PeriodicalMovementId $id
     * @return PeriodicalMovement
     */
    public function find(PeriodicalMovementId $id);

    /**
     * @return PeriodicalMovement[]
     */
    public function all();
}