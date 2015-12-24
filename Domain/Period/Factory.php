<?php

namespace Kontuak\Period;

use Kontuak\IsoDateTime;
use Kontuak\Period\Exception\IncorrectType;

class Factory
{
    const TYPE_DAY = 'day';
    const TYPE_MONTH_DAY = 'month_day';

    public static function fromType($type, $amount, IsoDateTime $startDate, IsoDateTime $endDate = null)
    {
        switch($type) {
            case self::TYPE_DAY:
                return new DaysPeriod($amount, $startDate, $endDate);
            case self::TYPE_MONTH_DAY:
                return new MonthDayPeriod($amount, $startDate, $endDate);
        }

        throw new IncorrectType($type);
    }
}
