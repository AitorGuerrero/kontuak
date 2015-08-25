<?php

namespace Kontuak;

interface PeriodicalMovementsCollection
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