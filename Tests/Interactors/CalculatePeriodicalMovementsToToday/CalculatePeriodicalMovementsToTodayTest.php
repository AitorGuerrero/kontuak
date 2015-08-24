<?php

namespace Kontuak\Tests\Interactors\CreateAPeriodicalEntry;

use Kontuak\Implementation\InMemory\PeriodicalMovementCollection;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalExpenditure;

class CalculatePeriodicalMovementsToTodayTest
{
    private $collection;

    public function calculatingDaysPeriods()
    {
        $this->collection = new PeriodicalMovementCollection();
        $periodicalMovement = new PeriodicalExpenditure(10, 'a', new DaysPeriod(2));
        $this->collection->add($periodicalMovement);
    }
}