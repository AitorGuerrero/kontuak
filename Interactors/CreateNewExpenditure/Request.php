<?php

namespace Kontuak\Interactors\CreateNewExpenditure;

class Request
{
    /** @var float */
    public $amount;
    /** @var string */
    public $concept;
    /** @var string */
    public $dateTimeSerialized;

    /**
     * @param float $amount
     * @param string $concept
     * @param string $dateTimeSerialized
     */
    public function __construct($amount, $concept, $dateTimeSerialized)
    {
        $this->amount = $amount;
        $this->concept = $concept;
        $this->dateTimeSerialized = $dateTimeSerialized;
    }
}