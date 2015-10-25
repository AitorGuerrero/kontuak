<?php

namespace Kontuak\Adapters\Transformer;

use Kontuak\PeriodicalMovement\Transformer;

class PeriodicalMovement implements Transformer
{

    /**
     * @param \Kontuak\PeriodicalMovement $entity
     * @return mixed
     */
    public function toResource(\Kontuak\PeriodicalMovement $entity)
    {
        return $entity;
    }
}