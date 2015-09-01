<?php

namespace Kontuak;

interface MovementsCollection
{

    /**
     * @return MovementsCollection
     */
    public function orderByDate();

    /**
     * @return MovementsCollection
     */
    public function orderByDateDesc();

    /**
     * @param int $amount
     * @return MovementsCollection
     */
    public function limit($amount);

    /**
     * @return Movement[]
     */
    public function toArray();

    /**
     * @param \DateTimeInterface $date
     * @return MovementsCollection
     */
    public function filterDateLessThan(\DateTimeInterface $date);

    /**
     * @param MovementId $id
     * @return MovementsCollection
     */
    public function filterById(MovementId $id);

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