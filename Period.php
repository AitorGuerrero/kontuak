<?php

namespace Kontuak;

abstract class Period
{
    const TYPE_DAY = 'day';
    const TYPE_MONTH_DAY = 'month_day';

    private $amount;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function amount()
    {
        return $this->amount;
    }

    /**
     * Should return a constant TYPE_* defined in this class
     * @return mixed
     */
    abstract function type();
}