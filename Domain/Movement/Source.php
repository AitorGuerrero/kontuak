<?php

namespace Kontuak\Movement;

use Kontuak\Exception\Source\DuplicatedId;
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

    /**
     * @param Movement $movement
     * @throws DuplicatedId
     * @return mixed
     */
    public function add(Movement $movement);

    /**
     * @param Movement $movement
     * @return void
     */
    public function remove(Movement $movement);

    /**
     * @param Id $id
     * @return Movement
     * @throws \Kontuak\Exception\Source\EntityNotFound
     */
    public function get(Id $id);
}