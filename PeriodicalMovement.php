<?php

namespace Kontuak;

class PeriodicalMovement
{
    use EntityTrait;

    /** @var int */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var Period */
    private $period;
    /** @var Movement */
    protected $templateMovement;
    /**
     * @var \DateTimeInterface
     */
    private $starts;
    /**
     * @var \DateTimeInterface
     */
    private $ends;

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

    /**
     * @param $amount
     * @param $concept
     * @param \DateTimeInterface $starts
     * @param Period $period
     */
    public function __construct(
        $amount,
        $concept,
        \DateTimeInterface $starts,
        Period $period
    ) {
        $this->amount = $amount;
        $this->concept = $concept;
        $this->period = $period;
        $this->starts = $starts;
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
}