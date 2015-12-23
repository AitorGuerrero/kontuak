<?php

namespace Kontuak\Period;

use Kontuak\Period;

class DaysPeriod extends Period
{
    function next(\DateTimeInterface $date)
    {
        $interval = new \DateInterval('P'.$this->amount().'D');
        $date->add($interval);

        return $date;
    }

    /**
     * Should return a constant TYPE_* defined in this class
     * @return mixed
     */
    function type()
    {
        return Period::TYPE_DAY;
    }
}