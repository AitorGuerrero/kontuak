<?php

namespace Kontuak\Tests\Interactors\PeriodicalMovement\Apply;

use Kontuak\Implementation\InMemory;
use Kontuak\Implementation\InMemory\Movement\Source;
use Kontuak\Interactors\PeriodicalMovement\Apply;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement;
use Kontuak\Movement;
use Kontuak\Implementation;

class Test extends \PHPUnit_Framework_TestCase
{
    const LIMIT_ISO_DATE = '2015-11-09';
    const CURRENT_ISO_DATE = '2015-08-09';
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

    protected function setUp()
    {
        $movementFactory = new InMemory\Movement\Factory();
        $periodicalMovementFactory = new InMemory\PeriodicalMovement\Factory();
        $idGenerator = new PeriodicalMovement\Id\Generator();
        $this->starts = '2015-08-01';
        $this->period = new DaysPeriod(3);
        $this->periodicalMovement = $periodicalMovementFactory->make(
            $idGenerator->generate(),
            10,
            'AA',
            new \DateTime($this->starts),
            $this->period
        );
        $this->timeStamp = new \DateTime(self::CURRENT_ISO_DATE);
        $this->movementsSource = new Source();
        $this->periodicalMovementsSource = new Implementation\PeriodicalMovement\Source\InMemory();
        $this->periodicalMovementsSource->add($this->periodicalMovement);
        $this->useCase = new Apply\UseCase(
            $this->movementsSource,
            $this->periodicalMovementsSource,
            $this->timeStamp,
            new PeriodicalMovement\MovementsGenerator(
                $this->movementsSource,
                new Movement\Id\Generator(),
                $movementFactory,
                $this->timeStamp
            )
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
     * @group PIS
     */
    public function shouldCreateToCurrentDateIncluded()
    {
        $this->useCase->execute();
        $movements = $this->movementsSource->collection()->orderByDate();
        $this->assertEquals(34, $movements->count());
        for($i = 1; $i < $movements->count(); $i++) {
            $movements->next();
        }
        $this->assertEquals('2015-11-08', $movements->current()->date()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function daysPeriod()
    {
        $this->useCase->execute();

        $movements = $this->movementsSource->collection()->orderByDate();
        $this->assertEquals(34, $movements->count());
        $this->assertEquals('2015-08-01', $movements->current()->date()->format('Y-m-d'));
        $movements->next();
        $this->assertEquals('2015-08-04', $movements->current()->date()->format('Y-m-d'));
        $movements->next();
        $this->assertEquals('2015-08-07', $movements->current()->date()->format('Y-m-d'));
    }

    /**
     * @test
     */
    public function shouldNotCreateAlreadyCreatedMovements()
    {
        $this->useCase->execute();
        $this->timeStamp->setDate(2015, 8, 20);
        $this->useCase->execute();
        $movements = $this->movementsSource->collection()->orderByDate();

        $this->assertEquals(38, $movements->count());
    }
}