<?php

namespace Kontuak;

use Kontuak\Period\DaysPeriod;
use Kontuak\Period\Exception\IncorrectType;
use Kontuak\Period\MonthDayPeriod;

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

    public static function factory($type, $amount)
    {
        switch($type) {
            case self::TYPE_DAY:
                return new DaysPeriod($amount);
            case self::TYPE_MONTH_DAY:
                return new MonthDayPeriod($amount);
        }

        throw new IncorrectType();
    }
}