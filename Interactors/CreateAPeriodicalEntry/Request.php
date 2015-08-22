<?php

namespace Kontuak\Interactors\CreateAPeriodicalEntry;

class Request {

    const TYPE_DAYS = 1;
    const TYPE_MONTHS = 2;
    const TYPE_WEEK_DAY = 3;
    const TYPE_MONTH_DAY = 4;

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