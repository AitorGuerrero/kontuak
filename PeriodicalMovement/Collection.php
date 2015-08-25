<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\EntityId;
use Kontuak\PeriodicalMovement;

interface Collection
{
    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return
     */
    public function add(PeriodicalMovement $periodicalMovement);

    /**
     * @param EntityId $entityId
     * @return PeriodicalMovement
     */
    public function find(EntityId $entityId);

    /**
     * @return PeriodicalMovement[]
     */
    public function all();
}