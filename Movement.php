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
     * @param float $amount
     */
    protected function updateAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param string $concept
     */
    protected function updateConcept($concept)
    {
        $this->concept = $concept;
    }

    /**
     * @param \DateTimeInterface $date
     */
    protected function updateDate($date)
    {
        $this->date = $date;
    }

    public function date()
    {
        return $this->date();
    }
}