<?php

namespace Kontuak\Movement;

use Kontuak\Movement;

/**
 * Interface MovementsSource
 * Represents de persistence layer for movements
 * This has to be implemented usually with a database system.
 * @package Kontuak
 */
interface Source
{
    /**
     * @return Collection
     */
    public function collection();

    public function add(Movement $movement);

    /**
     * @param Movement $movement
     * @return void
     */
    public function remove(Movement $movement);

    /**
     * @param Movement $movement
     */
    public function persist(Movement $movement);

    /**
     * @return Movement\Id
     */
    public function newId();
}