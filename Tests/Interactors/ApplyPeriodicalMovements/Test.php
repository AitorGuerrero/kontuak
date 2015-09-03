<?php

namespace Kontuak\Tests\Interactors\ApplyPeriodicalMovements;

use Kontuak\Implementation\InMemory\Movement;
use Kontuak\Implementation\InMemory\MovementsSource;
use Kontuak\Implementation\InMemory\PeriodicalMovementsSource;
use Kontuak\Interactors\ApplyPeriodicalMovements;
use Kontuak\Period\DaysPeriod;
use Kontuak\Period\WeekDayPeriod;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\MovementsGenerator;
use Kontuak\PeriodicalMovementId;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var PeriodicalMovementsSource */
    private $periodicalMovementsSource;
    /** @var ApplyPeriodicalMovements\UseCase */
    private $useCase;
    private $starts;
    private $period;
    /** @var PeriodicalMovement */
    private $periodicalMovement;
    /** @var \DateTime */
    private $timeStamp;
    private $timeStampFormatted;

    protected function setUp()
    {
        $this->starts = '2015-08-01';
        $this->period = new DaysPeriod(3);
        $this->periodicalMovement = new PeriodicalMovement(
            new PeriodicalMovementId(),
            10,
            'AA',
            new \DateTime($this->starts),
            $this->period
        );
        $this->timeStampFormatted = '2015-08-09';
        $this->timeStamp = new \DateTime('2015-08-09');
        $this->movementsSource = $movementsSource = new Movement\Source();
        $this->periodicalMovementsSource = new PeriodicalMovementsSource();
        $this->periodicalMovementsSource->add($this->periodicalMovement);
        $this->useCase = new ApplyPeriodicalMovements\UseCase(
            $movementsSource,
            $this->periodicalMovementsSource,
            $this->timeStamp,
            new MovementsGenerator($this->movementsSource, $this->timeStamp)
        );
    }

    /**
     * @test
     */
    public function shouldStartCreatingFromTheFirstDate()
    {
        $this->useCase->execute();

        $movement = $this->movementsSource->collection()->orderByDate()->current();
        $this->assertEquals('2015-08-01', $movement->date()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function shouldCreateToCurrentDateIncluded()
    {
        $this->timeStamp->setDate(2015, 8, 10);
        $this->useCase->execute();
        $movements = $this->movementsSource->collection()->orderByDate();
        $this->assertEquals(4, $movements->count());
        $movements->next();
        $movements->next();
        $this->assertEquals('2015-08-10', $movements->next()->date()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function daysPeriod()
    {
        $this->useCase->execute();

        $movements = $this->movementsSource->collection()->orderByDate();
        $this->assertEquals(3, $movements->count());
        $this->assertEquals('2015-08-01', $movements->current()->date()->format('Y-m-d'));
        $this->assertEquals('2015-08-04', $movements->next()->date()->format('Y-m-d'));
        $this->assertEquals('2015-08-07', $movements->next()->date()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function shouldNorCreateAlreadyCreatedMovements()
    {
        $this->useCase->execute();
        $this->timeStamp->setDate(2015, 8, 20);
        $this->useCase->execute();
        $movements = $this->movementsSource->collection()->orderByDate();

        $this->assertEquals(7, $movements->count());
    }
}