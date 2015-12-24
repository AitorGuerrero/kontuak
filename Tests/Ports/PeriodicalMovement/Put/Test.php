<?php

namespace Ports\PeriodicalMovement\Put;

use Kontuak\IsoDateTime;
use Kontuak\Ports\PeriodicalMovement\Put;
use Kontuak\Ports\PeriodicalMovement\Put\Request;
use Kontuak\Adapters\InMemory\PeriodicalMovement\Source;
use Kontuak\Period\DaysPeriod;
use kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Id;

class Test extends \PHPUnit_Framework_TestCase
{
    const CURRENT_ISO_DATE = '2015-08-01 00:00:00';
    const ISO_DATE = '2015-08-03';
    const AMOUNT = 10;
    const CONCEPT = 'Pis';
    const ID = '531d52c5-d217-4a94-92f3-3e0f9b603a7a';
    const PERIOD_TYPE = 'days';
    const PERIOD_AMOUNT = 5;

    /** @var Request */
    private $request;
    /** @var Put */
    private $useCase;
    /** @var Source */
    private $source;

    protected function setUp()
    {
        $this->source = new Source();
        $this->useCase = new Put(
            $this->source,
            new \DateTime(self::CURRENT_ISO_DATE)
        );

        $this->source->add(new PeriodicalMovement(
            Id::parse(self::ID),
            self::AMOUNT,
            self::CONCEPT,
            new DaysPeriod(4, new IsoDateTime(self::ISO_DATE)),
            new \DateTime(self::CURRENT_ISO_DATE)
        ));

        $this->request = new Request(
            self::ID,
            self::CONCEPT,
            self::AMOUNT,
            self::ISO_DATE,
            self::PERIOD_TYPE,
            self::PERIOD_AMOUNT
        );
    }

    /**
     * @test
     */
    public function shouldSaveTheMovement()
    {
        $this->useCase->execute($this->request);
        $movement = $this->source->byId(Id::parse(self::ID));

        $this->assertEquals(self::ID, $movement->id()->toString());
        $this->assertEquals(self::AMOUNT, $movement->amount());
        $this->assertEquals(self::CONCEPT, $movement->concept());
        $this->assertEquals(self::ISO_DATE, $movement->starts()->isoDate());
    }
}