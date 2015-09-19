<?php

namespace Kontuak\Interactors\Mappings;

use Kontuak\Period;

class PeriodicalMovement
{
    const PERIOD_TYPE_DAYS = 'days';
    const PERIOD_TYPE_MONTHS = 'months';

    public static $mapPeriodTypeToDomain = [
        self::PERIOD_TYPE_DAYS => Period::TYPE_DAY,
        self::PERIOD_TYPE_MONTHS => Period::TYPE_MONTH_DAY,
    ];
}