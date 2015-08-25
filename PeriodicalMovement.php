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
    /** @var \DateTimeInterface */
    private $starts;
    /** @var \DateTimeInterface */
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

    /**
     * @param \DateTimeInterface $from
     * @param \DateTimeInterface $to
     * @return Movement[]
     */
    public function generateMovements(\DateTimeInterface $from, \DateTimeInterface $to)
    {
        $date = $from->format('Y-m-d');
        $movements = [];
        while($date <= $to->format('Y-m-d')) {
            $movements[] = new Movement($this->amount(), $this->concept(), new \DateTime($date));
            $date = $this->period->next(new \DateTime($date))->format('Y-m-d');
        }
        return $movements;
    }

    public function updatePeriod(Period $period)
    {
        $this->period = $period;
    }
}