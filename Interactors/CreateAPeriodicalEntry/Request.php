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
    public $starts;
    public $ends;
}