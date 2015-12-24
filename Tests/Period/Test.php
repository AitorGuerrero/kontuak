<?php

namespace Kontuak\Tests\Period;

use Kontuak\IsoDateTime;
use Kontuak\Period;
use Kontuak\Period\MonthDayPeriod;

class Test extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ifTheDayIsLargerThanMonthDaysShouldGetTheLastDayOfMonth()
    {
        $monthsAmount = 1;
        $dateFrom = new IsoDateTime('2015-01-31');
        $period = new MonthDayPeriod($monthsAmount, $dateFrom);
        $nextDate = $period->next($dateFrom);

        $this->assertEquals('2015-02-28', $nextDate->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function changesWellTheYear()
    {
        $monthsAmount = 1;
        $dateFrom = new IsoDateTime('2015-12-15');
        $period = new MonthDayPeriod($monthsAmount, $dateFrom);
        $nextDate = $period->next($dateFrom);

        $this->assertEquals('2016-01-15', $nextDate->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function whenCreatingIfTheTypeIsIncorrectShouldThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Period\Exception\IncorrectType');
        Period\Factory::fromType('inexistent type', 10, new IsoDateTime());
    }
}