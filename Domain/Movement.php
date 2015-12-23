<?php

namespace Kontuak;

use Kontuak\Movement\Exception\InvalidAmount;
use Kontuak\Movement\Id as MovementId;

class Movement
{
    private $emptyConceptMessage = '"concept" should not be blank';

    /** @var MovementId */
    protected $id;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var \DateTime */
    protected $date;
    /** @var \DateTime */
    protected $created;
    /** @var PeriodicalMovement|null */
    protected $periodicalMovement;


    public function __construct(
        MovementId $id,
        $amount,
        $concept,
        \DateTime $date,
        \DateTime $created
    ) {
        $this->id = $id;
        $this->updateAmount($amount);
        $this->updateConcept($concept);
        $this->updateDate($date);
        $this->created = $created;
    }

    /**
     * @return MovementId
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

    /**
     * @return \DateTime
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
     * @param \DateTime $date
     */
    public function updateDate(\DateTime $date)
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