<?php

namespace Kontuak;

interface PeriodicalMovementsCollection
{
    public function add(PeriodicalMovement $periodicalMovement);

    public function find(EntityId $entityId);
}