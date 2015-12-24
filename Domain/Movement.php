<?php

namespace Kontuak;

use Kontuak\Movement\Exception\InvalidAmount;
use Kontuak\Movement\Exception\InvalidConcept;
use Kontuak\Movement\Id;
use Kontuak\DateTime;

class Movement
{
    /** @var Id */
    protected $id;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var DateTime */
    protected $date;
    /** @var DateTime */
    protected $created;
    /** @var PeriodicalMovement|null */
    protected $periodicalMovement;


    public function __construct(
        Id $id,
        $amount,
        $concept,
        DateTime $date,
        DateTime $created
    ) {
        $this->id = $id;
        $this->setAmount($amount);
        $this->setConcept($concept);
        $this->date = $date;
        $this->created = $created;
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
     * @return DateTime
     */
    public function date()
    {
        return $this->date;
    }

    /**
     * @return DateTime
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
     * @param DateTime $date
     */
    public function updateDate(DateTime $date)
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