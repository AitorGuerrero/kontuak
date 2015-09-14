<?php

namespace Kontuak;

use Kontuak\Movement\Exception\InvalidAmount;
use Kontuak\Movement\Id;

class Movement
{
    private $emptyConceptMessage = '"concept" should not be blank';

    /** @var Id */
    protected $id;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var \DateTimeInterface */
    protected $date;
    /** @var \DateTimeInterface */
    protected $created;
    /** @var PeriodicalMovement|null */
    protected $periodicalMovement;


    public function __construct(
        Id $movementId,
        $amount,
        $concept,
        \DateTimeInterface $date,
        \DateTimeInterface $created)
    {
        $this->id = $movementId;
        $this->updateAmount($amount);
        $this->updateConcept($concept);
        $this->updateDate($date);
        $this->created = $created;
    }

    /**
     * @return Movement\Id
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
     * @return \DateTime
     */
    public function date()
    {
        return $this->date;
    }

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
        if (!is_numeric($amount) || 0 == $amount) {
            throw new InvalidAmount();
        }
        $this->amount = (float) $amount;
    }

    /**
     * @param string $concept
     * @throws InvalidArgumentException
     */
    public function updateConcept($concept)
    {
        if(empty($concept)) {
            throw new InvalidArgumentException($this->emptyConceptMessage);
        }
        $this->concept = $concept;
    }

    /**
     * @param \DateTimeInterface $date
     */
    public function updateDate(\DateTimeInterface $date)
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
}