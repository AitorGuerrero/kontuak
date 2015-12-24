<?php

namespace Kontuak\Period;

use Kontuak\Period\Exception\IncorrectType;

class Factory
{
    const TYPE_DAY = 'day';
    const TYPE_MONTH_DAY = 'month_day';

    public static function fromType($type, $amount)
    {
        switch($type) {
            case self::TYPE_DAY:
                return new DaysPeriod($amount);
            case self::TYPE_MONTH_DAY:
                return new MonthDayPeriod($amount);
        }

        throw new IncorrectType($type);
    }
}
