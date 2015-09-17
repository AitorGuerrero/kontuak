<?php

namespace Kontuak;

class PeriodicalMovement
{
    /** @var PeriodicalMovementId */
    protected $id;
    /** @var int */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var Period */
    protected $period;
    /** @var Movement */
    protected $templateMovement;
    /** @var \DateTimeInterface */
    protected $starts;
    /** @var \DateTimeInterface */
    protected $ends;

    /**
     * @param PeriodicalMovement\Id $id
     * @param $amount
     * @param $concept
     * @param \DateTime $starts
     * @param Period $period
     */
    protected function __construct(
        PeriodicalMovement\Id $id,
        $amount,
        $concept,
        \DateTime $starts,
        Period $period
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->concept = $concept;
        $this->period = $period;
        $this->starts = $starts;
    }

    /**
     * @return PeriodicalMovement\Id
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function concept()
    {
        return $this->concept;
    }

    /**
     * @return Period
     */
    public function period()
    {
        return $this->period;
    }

    public function endsAt(\DateTimeInterface $date)
    {
        $this->ends = $date;
    }

    /**
     * @return int
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @param Period $period
     */
    public function updatePeriod(Period $period)
    {
        $this->period = $period;
    }

    /**
     * @return \DateTimeInterface
     */
    public function starts()
    {
        return $this->starts;
    }
}