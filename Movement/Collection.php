<?php

namespace Kontuak\Movement;

use Kontuak\PeriodicalMovement;

interface Collection extends \Iterator, \Countable
{

    /**
     * @return Collection
     */
    public function orderByDate();

    /**
     * @return Collection
     */
    public function orderByDateDesc();

    /**
     * @param \DateTimeInterface $date
     * @return Collection
     */
    public function filterDateLessThan(\DateTimeInterface $date);

    /**
     * @param \DateTimeInterface $date
     * @return $this
     */
    public function filterDateLessOrEqualTo(\DateTimeInterface $date);

    /**
     * @param \DateTimeInterface $dateTime
     * @return Collection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime);

    /**
     * @param \DateTimeInterface $date
     * @return Collection
     */
    public function filterByDateIs(\DateTimeInterface $date);

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return Collection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement);

    /**
     * @return float
     */
    public function amountSum();

    /**
     * @param \DateTime $timeStamp
     * @return Collection
     */
    public function filterByDateIsPostThan(\DateTime $timeStamp);
}