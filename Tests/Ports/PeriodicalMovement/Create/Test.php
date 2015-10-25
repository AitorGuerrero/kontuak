<?php

namespace Ports\PeriodicalMovement\Create;

use Kontuak\Adapters\InMemory\PeriodicalMovement\Factory;
use Kontuak\Adapters\InMemory\PeriodicalMovement\Source;
use Kontuak\Ports\Mappings\PeriodicalMovement;
use Kontuak\Ports\PeriodicalMovement\Create\UseCase;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;

class Test extends \PHPUnit_Framework_TestCase
{
    const ID = '54a4d0f3-5a35-4309-bc21-3205ed3e6a6b';
    const CONCEPT = 'Concept';
    const AMOUNT = 10;
    const STARTS = '2015-06-01';
    const PERIOD_AMOUNT = 3;

    /**
     * @test
     */
    public function shouldAddToTheSource()
    {
        $source = new Source();
        $useCase = new UseCase($source);
        $request = $useCase->newRequest();
        $request->id = self::ID;
        $request->concept = self::CONCEPT;
        $request->amount = self::AMOUNT;
        $request->starts = self::STARTS;
        $request->periodType = PeriodicalMovement::PERIOD_TYPE_DAYS;
        $request->periodAmount = self::PERIOD_AMOUNT;
        $useCase->execute($request);

        $id = Id::parse(self::ID);
        $period = new Period\DaysPeriod(self::PERIOD_AMOUNT);
        $periodicalMovement = $source->get($id);
        $this->assertEquals(self::CONCEPT, $periodicalMovement->concept());
        $this->assertEquals(self::AMOUNT, $periodicalMovement->amount());
        $this->assertEquals($period, $periodicalMovement->period());
        $this->assertEquals(self::STARTS, $periodicalMovement->starts()->format('Y-m-d'));
    }
}