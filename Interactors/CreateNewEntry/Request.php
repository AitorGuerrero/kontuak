<?php

namespace Kontuak\Interactors\CreateNewEntry;

class Request
{
    /** @var float  */
    public $amount;
    /** @var string */
    public $concept;
    /** @var \DateTimeInterface */
    public $date;

    /**
     * @param float $amount
     * @param string $concept
     * @param string $date
     */
    public function __construct($amount, $concept, $date)
    {
        $this->amount = $amount;
        $this->concept = $concept;
        $this->date = $date;
    }
}