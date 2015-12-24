<?php

namespace Kontuak\Period;

use Kontuak\Period;

class MonthDayPeriod extends Period
{
    function next(\DateTimeInterface $date)
    {
        $targetDay = (int) $date->format('d');
        $date->setDate($date->format('Y'), (int) $date->format('m') + $this->amount(), 1);
        $targetMonth = (int) $date->format('m');
        $date->setDate($date->format('Y'), $date->format('m'), $targetDay);
        if((int) $date->format('m') !== $targetMonth) {
            $date->setDate($date->format('Y'), $date->format('m'), 0);
        }

        return $date;
    }
}