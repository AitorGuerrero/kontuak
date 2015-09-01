<?php

namespace Kontuak;

interface PeriodicalMovementsSource
{
    /**
     * @return \Kontuak\PeriodicalMovement\Collection
     */
    public function collection();

    public function add(PeriodicalMovement $movement);
}