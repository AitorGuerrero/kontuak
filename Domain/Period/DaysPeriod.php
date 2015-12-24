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
}