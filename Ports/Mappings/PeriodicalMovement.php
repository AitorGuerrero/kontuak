<?php

namespace Kontuak\Ports\Mappings;

use Kontuak\Period;

class PeriodicalMovement
{
    const PERIOD_TYPE_DAYS = 'days';
    const PERIOD_TYPE_MONTHS = 'months';

    public static $mapPeriodTypeToDomain = [
        self::PERIOD_TYPE_DAYS => Period\Factory::TYPE_DAY,
        self::PERIOD_TYPE_MONTHS => Period\Factory::TYPE_MONTH_DAY,
    ];
}