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
    private $period;
    /** @var Movement */
    protected $templateMovement;
    /** @var \DateTimeInterface */
    protected $starts;
    /** @var \DateTimeInterface */
    protected $ends;

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
     * @param PeriodicalMovementId $id
     * @param $amount
     * @param $concept
     * @param \DateTimeInterface $starts
     * @param Period $period
     */
    public function __construct(
        PeriodicalMovementId $id,
        $amount,
        $concept,
        \DateTimeInterface $starts,
        Period $period
    ) {
        $this->amount = $amount;
        $this->concept = $concept;
        $this->period = $period;
        $this->starts = $starts;
        $this->id = $id;
    }

    /**
     * @return PeriodicalMovementId
     */
    public function id()
    {
        return $this->id;
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
     * @param \DateTimeInterface $to
     * @return Movement[]
     */
    public function generateMovements(\DateTimeInterface $to)
    {
        $date = $this->starts->format('Y-m-d');
        $toFormatted = $to->format('Y-m-d');
        $movements = [];
        while($date <= $toFormatted) {
            $movements[] = new Movement(
                new MovementId(),
                $this->amount(),
                $this->concept(),
                new \DateTime($date)
            );
            $date = $this->period->next(new \DateTime($date))->format('Y-m-d');
        }
        return $movements;
    }

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