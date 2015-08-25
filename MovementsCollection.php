<?php

namespace Kontuak;

interface MovementsCollection
{
    /**
     * @param Movement $movement
     * @return
     */
    public function add(Movement $movement);
    /**
     * @param EntityId $id
     * @return Movement
     */
    public function find(EntityId $id);

    public function orderByDate();

    public function orderByDateDesc();

    /**
     * @param int $amount
     */
    public function limit($amount);

    /**
     * @return Movement[]
     */
    public function all();

    /**
     * @param \DateTimeInterface $date
     * @return MovementsCollection
     */
    public function filterDateLessThan(\DateTimeInterface $date);

    /**
     * float
     */
    public function amountSum();

    /**
     * @param \DateTimeInterface $dateTime
     * @return MovementsCollection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime);

    /**
     * @param \DateTimeInterface $date
     * @return MovementsCollection
     */
    public function filterByDateIs(\DateTimeInterface $date);

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return MovementsCollection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement);

    /**
     * @return Movement
     */
    public function first();
}