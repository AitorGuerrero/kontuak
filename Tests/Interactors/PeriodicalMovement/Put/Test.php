<?php

namespace Interactors\PeriodicalMovement\Put;

use Kontuak\Interactors\PeriodicalMovement\Put\Request;
use Kontuak\Interactors\PeriodicalMovement\Put\UseCase;
use Kontuak\Adapters\InMemory\PeriodicalMovement\Factory;
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

    /** @var Factory */
    private $periodicalMovementFactory;
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var Source */
    private $source;

    protected function setUp()
    {
        $this->source = new Source();
        $this->periodicalMovementFactory = new Factory();
        $this->useCase = new UseCase(
            $this->source,
            new \Kontuak\Adapters\Transformer\PeriodicalMovement(),
            $this->periodicalMovementFactory,
            new \DateTime(self::CURRENT_ISO_DATE)
        );

        $this->source->add($this->periodicalMovementFactory->make(
            new Id(self::ID),
            self::AMOUNT,
            self::CONCEPT,
            new \DateTime(self::ISO_DATE),
            new DaysPeriod(4),
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
    public function whenMovementDoesNotExistsShouldReturnCreated()
    {
        $response = $this->useCase->execute(new Request(
            'newId',
            self::CONCEPT,
            self::AMOUNT,
            self::ISO_DATE,
            self::PERIOD_TYPE,
            self::PERIOD_AMOUNT
        ));
        $this->assertTrue($response->isNew());
    }

    /**
     * @test
     */
    public function whenMovementExistsShouldReturnNotCreated()
    {
        $response = $this->useCase->execute($this->request);

        $this->assertFalse($response->isNew());
    }

    /**
     * @test
     */
    public function shouldSaveTheMovement()
    {
        $this->useCase->execute($this->request);
        $movement = $this->source->byId(new Id(self::ID));

        $this->assertEquals(self::ID, $movement->id()->serialize());
        $this->assertEquals(self::AMOUNT, $movement->amount());
        $this->assertEquals(self::CONCEPT, $movement->concept());
        $this->assertEquals(self::ISO_DATE, $movement->starts()->format('Y-m-d'));
    }
}