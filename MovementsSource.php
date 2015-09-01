<?php

namespace Kontuak;

/**
 * Interface MovementsSource
 * Represents de persistence layer for movements
 * This has to be implemented usually with a database system.
 * @package Kontuak
 */
interface MovementsSource
{
    /**
     * @return MovementsCollection
     */
    public function collection();

    public function add(Movement $movement);
}