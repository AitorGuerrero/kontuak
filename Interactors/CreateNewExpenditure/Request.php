<?php

namespace kontuak\Interactors\CreateNewExpenditure;

class Request
{
    /**
     * @var float
     */
    public $amount;
    /**
     * @var string
     */
    public $concept;

    /**
     * @param float $amount
     * @param string $concept
     */
    public function __construct($amount, $concept)
    {
        $this->amount = $amount;
        $this->concept = $concept;
    }
}