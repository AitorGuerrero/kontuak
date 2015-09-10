<?php

namespace Kontuak\Tests\Interactors\PeriodicalMovement\Apply;

use Kontuak\Implementation\InMemory;
use Kontuak\Interactors\PeriodicalMovement\Apply;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement;
use Kontuak\Movement;
use Kontuak\Implementation;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var \Kontuak\Implementation\PeriodicalMovement\Source\InMemory */
    private $periodicalMovementsSource;
    /** @var Apply\UseCase */
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
            new PeriodicalMovement\Id(),
            10,
            'AA',
            new \DateTime($this->starts),
            $this->period
        );
        $this->timeStampFormatted = '2015-08-09';
        $this->timeStamp = new \DateTime('2015-08-09');
        $this->movementsSource = new Implementation\Movement\Source\InMemory();
        $this->periodicalMovementsSource = new Implementation\PeriodicalMovement\Source\InMemory();
        $this->periodicalMovementsSource->add($this->periodicalMovement);
        $this->useCase = new Apply\UseCase(
            $this->movementsSource,
            $this->periodicalMovementsSource,
            $this->timeStamp,
            new PeriodicalMovement\MovementsGenerator($this->movementsSource, $this->timeStamp)
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
        $movements->next();
        $this->assertEquals('2015-08-10', $movements->current()->date()->format('Y-m-d'));
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
        $movements->next();
        $this->assertEquals('2015-08-04', $movements->current()->date()->format('Y-m-d'));
        $movements->next();
        $this->assertEquals('2015-08-07', $movements->current()->date()->format('Y-m-d'));
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