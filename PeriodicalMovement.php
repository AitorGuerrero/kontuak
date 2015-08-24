<?php

namespace Kontuak;

use DateTime;

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
     * @param \DateTimeInterface $ends
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
     * @param DateTime $toDate
     * @return PeriodicalMovement[]
     */
    public function generateTo(DateTime $toDate)
    {
        $dates = $this->predictDates($toDate);
        $movements = $this->createMovementsForDates($dates);

        return $movements;
    }

    private function generateMovementAtDate(DateTime $date)
    {
        if(null === $this->templateMovement) {
            $this->templateMovement = new $this->generateTemplateMovement();
        }
        $movement = clone($this->templateMovement);
        $movement->setDate($date);

        return $movement;
    }

    /**
     * @param DateTime $toDate
     * @return array
     */
    protected function predictDates(DateTime $toDate)
    {
        $dates = [];
        $date = new DateTime();
        $this->period->setHead($date);
        while ($date <= $toDate) {
            $dates[] = $date;
            $date = $this->period->next();
        }
        return array($dates, $date);
    }

    /**
     * @param $dates
     * @return array
     */
    protected function createMovementsForDates($dates)
    {
        $movements = [];
        foreach ($dates as $date) {
            $movements[] = $this->generateMovementAtDate($date);
        }
        return $movements;
    }
}