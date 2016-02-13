<?php

namespace Kontuak;

use DateTimeImmutable;
use Kontuak\EventManagement\EventPublisher;
use Kontuak\PeriodicalMovement\Event;

class PeriodicalMovement
{
    /** @var PeriodicalMovement\Id */
    protected $id;
    /** @var int */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var Period */
    protected $period;

    /**
     * @param PeriodicalMovement\Id $id
     * @param $amount
     * @param $concept
     * @param Period $period
     * @param DateTimeImmutable $currentDateTime
     */
    public function __construct(
        PeriodicalMovement\Id $id,
        $amount,
        $concept,
        Period $period,
        DateTimeImmutable $currentDateTime
    ) {
        $this->id = $id;
        $this->amount = $amount;
        $this->concept = $concept;
        $this->period = $period;
        EventPublisher::publish(new Event\Created($this, $currentDateTime));
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

    public function endsAt(IsoDateTime $date)
    {
        $this->period()->endsAt($date);
    }

    /**
     * @return int
     */
    public function amount()
    {
        return $this->amount;
    }

    public function updatePeriod(Period $period)
    {
        $oldValue = $this->period();
        $this->period = $period;
        EventPublisher::publish(new Event\PeriodUpdated(
            $this,
            $oldValue,
            $period
        ));
    }

    /**
     * @param int $amount
     */
    public function updateAmount($amount)
    {
        $oldValue = $this->amount();
        $this->amount = $amount;
        EventPublisher::publish(new Event\AmountUpdated(
            $this,
            $oldValue,
            $amount
        ));
    }

    /**
     * @param string $concept
     */
    public function updateConcept($concept)
    {
        $oldValue = $this->concept;
        $this->concept = $concept;
        EventPublisher::publish(new Event\ConceptUpdated(
            $this,
            $oldValue,
            $concept
        ));
    }
}