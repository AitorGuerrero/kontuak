<?php

namespace kontuak\Interactors\CreateAPeriodicalExpenditure;

class Request {

    const TYPE_DAYS = 0;
    const TYPE_MONTHS = 1;
    const TYPE_WEEK_DAY = 2;
    const TYPE_MONTH_DAY = 3;

    public $amount;
    public $concept;
    public $periodType;
    public $periodAmount;

    public function __construct($amount, $concept, $periodType, $periodAmount)
    {
        $this->amount = $amount;
        $this->concept = $concept;
        $this->periodType = $periodType;
        $this->periodAmount = $periodAmount;
    }
}