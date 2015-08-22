<?php

namespace Kontuak;

abstract class Movement
{
    /** @var float */
    protected $amount;
    /** @var string */
    protected $concept;
    /** @var \DateTimeInterface */
    protected $date;

    public function __construct($amount, $concept, \DateTimeInterface $date)
    {
        $this->updateAmount($amount);
        $this->updateConcept($concept);
        $this->updateDate($date);
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
        return $this->date();
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
}