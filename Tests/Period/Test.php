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
        $dateFrom = new IsoDateTime('2015-01-31');
        $period = new MonthDayPeriod($dateFrom);
        $period->next();

        $this->assertEquals('2015-02-28', $period->current()->isoDate());
    }

    /**
     * @test
     */
    public function changesWellTheYear()
    {
        $dateFrom = new IsoDateTime('2015-12-15');
        $period = new MonthDayPeriod($dateFrom);
        $period->next();

        $this->assertEquals('2016-01-15', $period->current()->isoDate());
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