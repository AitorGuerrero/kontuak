<?php

namespace Kontuak\Tests\Interactors\ApplyPeriodicalMovements;

use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Implementation\InMemory\PeriodicalMovementCollection;
use Kontuak\Interactors\ApplyPeriodicalMovements\UseCase;
use Kontuak\Period\DaysPeriod;
use Kontuak\Period\WeekDayPeriod;
use Kontuak\PeriodicalMovement;

class Test extends \PHPUnit_Framework_TestCase
{
    private $useCase;
    private $periodicalMovementsCollection;
    private $starts;
    private $period;
    /** @var PeriodicalMovement */
    private $periodicalMovement;
    /** @var \DateTime */
    private $timeStamp;
    /** @var MovementsCollection */
    private $movementsCollection;
    private $timeStampFormated;

    protected function setUp()
    {
        $this->starts = '2015-08-01';
        $this->period = new DaysPeriod(3);
        $this->periodicalMovement = new PeriodicalMovement(10, 'AA', new \DateTime($this->starts), $this->period);
        $this->timeStampFormated = '2015-08-09';
        $this->timeStamp = new \DateTime('2015-08-09');
        $this->movementsCollection = new MovementsCollection(new \DateTime($this->timeStampFormated));
        $this->periodicalMovementsCollection = new PeriodicalMovementCollection();
        $this->periodicalMovementsCollection->add($this->periodicalMovement);
        $this->useCase = new UseCase(
            $this->movementsCollection,
            $this->periodicalMovementsCollection,
            new \DateTime($this->timeStampFormated)
        );
    }

    /**
     * @test
     */
    public function shouldStartCreatingFromTheFirstDate()
    {
        $this->useCase->execute();

        $movements = $this->movementsCollection->all();
        $this->assertEquals('2015-08-01', $movements[0]->date()->format('Y-m-d'));
    }

    public function shouldCreateToCurrentDateIncluded()
    {
        $this->timeStamp->setDate(2015, 8, 10);
        $this->useCase->execute();
        $movements = $this->movementsCollection->all();
        $this->assertEquals(4, count($movements));
        $this->assertEquals('2015-08-10', $movements[3]->date()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function daysPeriod()
    {
        $this->useCase->execute();

        $movements = $this->movementsCollection->all();
        $this->assertEquals(3, count($movements));
        $this->assertEquals('2015-08-01', $movements[0]->date()->format('Y-m-d'));
        $this->assertEquals('2015-08-04', $movements[1]->date()->format('Y-m-d'));
        $this->assertEquals('2015-08-07', $movements[2]->date()->format('Y-m-d'));
    }
}