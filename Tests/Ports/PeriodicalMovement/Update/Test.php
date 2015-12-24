<?php

namespace Ports\PeriodicalMovement\Update;

use Kontuak\Adapters\InMemory\PeriodicalMovement\Source;
use Kontuak\IsoDateTime;
use Kontuak\Ports\Mappings\PeriodicalMovement;
use Kontuak\Ports\PeriodicalMovement\Update\Request;
use Kontuak\Ports\PeriodicalMovement\Update;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id;

class Test extends \PHPUnit_Framework_TestCase
{
    const ID = '54a4d0f3-5a35-4309-bc21-3205ed3e6a6b';
    const CONCEPT = 'Concept';
    const AMOUNT = 10;
    const STARTS = '2015-06-01';
    const PERIOD_AMOUNT = 4;
    const PERIOD_TYPE = PeriodicalMovement::PERIOD_TYPE_DAYS;

    const NEW_CONCEPT = 'new concept';
    const NEW_AMOUNT = 20;
    const NEW_START_DATE = '2015-06-20';
    const NEW_PERIOD_TYPE = PeriodicalMovement::PERIOD_TYPE_MONTHS;
    const NEW_PERIOD_AMOUNT = 3;

    /** @var Source */
    public $source;
    /** @var Update */
    public $useCase;
    /** @var Request */
    public $request;

    protected function setUp()
    {
        $this->source = new Source();
        $this->useCase = new Update($this->source);
        $this->request = $this->useCase->newRequest();
        $this->request->id = self::ID;
        $this->request->concept = self::NEW_CONCEPT;
        $this->request->amount = self::NEW_AMOUNT;
        $this->request->starts = self::NEW_START_DATE;
        $this->request->periodType = self::NEW_PERIOD_TYPE;
        $this->request->periodAmount = self::NEW_PERIOD_AMOUNT;

        $periodicalMovement = new \Kontuak\PeriodicalMovement(
            Id::parse(self::ID),
            self::AMOUNT,
            self::CONCEPT,
            new \DateTime(self::STARTS),
            Period\Factory::fromType(Period\Factory::TYPE_DAY, self::PERIOD_AMOUNT, new IsoDateTime(self::STARTS))
        );
        $this->source->add($periodicalMovement);
    }

    /**
     * @test
     */
    public function ifThePeriodicalMovementDoesNotExistsShouldThrowAnException()
    {
        $notExistentId = '5df09125-7cc0-4686-af55-733765c04103';
        $this->setExpectedException('\Kontuak\Ports\Exception\EntityNotFound');

        $this->request->id = $notExistentId;
        $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldUpdateTheFields()
    {
        $this->useCase->execute($this->request);

        $periodicalMovement = $this->source->get(Id::parse(self::ID));

        $this->assertEquals(self::NEW_CONCEPT, $periodicalMovement->concept());
        $this->assertEquals(self::NEW_AMOUNT, $periodicalMovement->amount());
        $this->assertEquals(self::NEW_START_DATE, $periodicalMovement->starts()->isoDate());
        $this->assertEquals(
            new Period\MonthDayPeriod(self::NEW_PERIOD_AMOUNT, $periodicalMovement->starts()),
            $periodicalMovement->period()
        );
    }
}