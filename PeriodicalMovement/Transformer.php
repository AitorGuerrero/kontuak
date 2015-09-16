<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

interface Transformer
{
    /**
     * @param PeriodicalMovement $entity
     * @return mixed
     */
    public function toResource(PeriodicalMovement $entity);
}