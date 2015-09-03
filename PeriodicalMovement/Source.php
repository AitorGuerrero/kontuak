<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

interface Source
{
    /**
     * @return \Kontuak\PeriodicalMovement\Collection
     */
    public function collection();

    public function add(PeriodicalMovement $movement);

    public function toArray();
}