<?php

namespace Kontuak\Tests\Period;

use Kontuak\Period\MonthDayPeriod;

class MonthDayPeriodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ifTheDayIsLargerThanMonthDaysShouldGetTheLastDayOfMonth()
    {
        $monthsAmount = 1;
        $dateFrom = new \DateTime('2015-01-31');
        $period = new MonthDayPeriod($monthsAmount);
        $nextDate = $period->next($dateFrom);

        $this->assertEquals('2015-02-28', $nextDate->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function changesWellTheYear()
    {
        $monthsAmount = 1;
        $dateFrom = new \DateTime('2015-12-15');
        $period = new MonthDayPeriod($monthsAmount);
        $nextDate = $period->next($dateFrom);

        $this->assertEquals('2016-01-15', $nextDate->format('Y-m-d'));
    }
}