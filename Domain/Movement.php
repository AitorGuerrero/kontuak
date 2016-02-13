<?php

namespace Kontuak;

use Kontuak\EventManagement\EventPublisher;
use Kontuak\Movement\Exception\InvalidAmount;
use Kontuak\Movement\Exception\InvalidConcept;
use Kontuak\Movement\Id;

class Movement
{
    /** @var Id */
    protected $id;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var IsoDateTime */
    protected $date;
    /** @var IsoDateTime */
    protected $created;
    /** @var PeriodicalMovement|null */
    protected $periodicalMovement;


    public function __construct(
        Id $id,
        $amount,
        $concept,
        IsoDateTime $date,
        IsoDateTime $created
    ) {
        $this->id = $id;
        $this->setAmount($amount);
        $this->setConcept($concept);
        $this->date = $date;
        $this->created = $created;
    }

    static function fromPeriodicalMovement(
        Id $id,
        IsoDateTime $date,
        IsoDateTime $created,
        PeriodicalMovement $periodicalMovement
    ) {
        $movement = new static(
            $id,
            $periodicalMovement->amount(),
            $periodicalMovement->concept(),
            $date,
            $created
        );
        EventManager::subscribe($movement, $periodicalMovement, 'Kontuak\PeriodicalMovement\Event\Updated');
        return $movement;
    }

    /**
     * @return Id
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return IsoDateTime
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @return IsoDateTime
     */
    public function created()
    {
        return $this->created;
    }

    /**
     * @param float $amount
     * @throws InvalidAmount
     */
    public function updateAmount($amount)
    {
        $this->setAmount($amount);
    }

    /**
     * @param string $concept
     * @throws InvalidArgumentException
     */
    public function updateConcept($concept)
    {
        $this->setConcept($concept);
    }

    /**
     * @param IsoDateTime $date
     */
    public function updateDate(IsoDateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function concept()
    {
        return $this->concept;
    }

    public function assignToPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $this->periodicalMovement = $periodicalMovement;
    }

    public function periodicalMovement()
    {
        return $this->periodicalMovement;
    }

    private function setAmount($amount)
    {
        $this->guardFromInvalidAmount($amount);
        $this->amount = (float) $amount;
    }

    private function setConcept($concept)
    {
        $this->guardFromInvalidConcept($concept);
        $this->concept = $concept;
    }

    /**
     * @param $amount
     * @throws InvalidAmount
     */
    public function guardFromInvalidAmount($amount)
    {
        if (!is_numeric($amount) || 0 === $amount) {
            throw new InvalidAmount();
        }
    }

    /**
     * @param $concept
     * @throws InvalidConcept
     */
    public function guardFromInvalidConcept($concept)
    {
        if (empty($concept)) {
            throw new InvalidConcept();
        }
    }
}