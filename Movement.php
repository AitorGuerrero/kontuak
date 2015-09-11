<?php

namespace Kontuak;

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
    private $periodicalMovement;


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
     * @param PeriodicalMovement $periodicalMovement
     * @param \DateTimeInterface $date
     * @return Movement
     * @TODO As a service
     */
    public static function fromPeriodicalMovement(PeriodicalMovement $periodicalMovement, \DateTimeInterface $date)
    {
        $generator = new Id\Generator(); // TODO Injection
        $movement = new self(
            $generator->generate(),
            $periodicalMovement->amount(),
            $periodicalMovement->concept(),
            $date,
            new \DateTime() // TODO Injection
        );
        $movement->assignToPeriodicalMovement($periodicalMovement);

        return $movement;
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
     */
    public function updateAmount($amount)
    {
        $this->amount = $amount;
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

    private function assignToPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $this->periodicalMovement = $periodicalMovement;
    }

    public function periodicalMovement()
    {
        return $this->periodicalMovement;
    }
}