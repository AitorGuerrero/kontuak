<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

interface Source
{
    /**
     * @return \Kontuak\PeriodicalMovement\Collection
     */
    public function collection();

    /**
     * @param PeriodicalMovement $movement
     * @throws \Kontuak\Exception\Source\DuplicatedId
     * @throws \Kontuak\Exception\Source\MalformedId
     */
    public function add(PeriodicalMovement $movement);

    /**
     * @param $param
     * @return PeriodicalMovement
     * @throws \Kontuak\Exception\Source\EntityNotFound
     */
    public function get(Id $param);
}