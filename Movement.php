<?php

namespace Kontuak;

class Movement
{

    /** @var MovementId */
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


    public function __construct(MovementId $movementId, $amount, $concept, \DateTimeInterface $date)
    {
        $this->id = $movementId;
        $this->updateAmount($amount);
        $this->updateConcept($concept);
        $this->updateDate($date);
    }

    /**
     * @return MovementId
     */
    public function id()
    {
        return $this->id;
    }

    public static function fromPeriodicalMovement(PeriodicalMovement $periodicalMovement, \DateTimeInterface $date)
    {
        $movement = new self(
            new MovementId(),
            $periodicalMovement->amount(),
            $periodicalMovement->concept(),
            $date
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

    public function setCreated(\DateTimeInterface $created)
    {
        $this->created = $created;
    }

    /**
     * @param float $amount
     */
    protected function updateAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param string $concept
     * @throws InvalidArgumentException
     */
    protected function updateConcept($concept)
    {
        if(empty($concept)) {
            throw new InvalidArgumentException();
        }
        $this->concept = $concept;
    }

    /**
     * @param \DateTimeInterface $date
     */
    protected function updateDate($date)
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