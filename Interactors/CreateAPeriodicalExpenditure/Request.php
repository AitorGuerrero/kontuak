<?php

namespace kontuak\Interactors\CreateAPeriodicalExpenditure;

class Request {

    const TYPE_DAYS = 1;
    const TYPE_MONTHS = 2;
    const TYPE_WEEK_DAY = 3;
    const TYPE_MONTH_DAY = 4;

    /** @var float */
    public $amount;
    /** @var string */
    public $concept;
    /** @var int */
    public $periodType;
    /** @var int */
    public $periodAmount;
    /** @var string */
    public $starts;
    /** @var string */
    public $ends;
}