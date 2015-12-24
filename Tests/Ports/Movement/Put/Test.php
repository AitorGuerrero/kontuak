<?php

namespace Ports\Movement\Put;

use Kontuak\DateTime;
use Kontuak\Ports\Movement\Put;
use kontuak\Movement;
use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\Movement\Id;

class Test extends \PHPUnit_Framework_TestCase
{
    const CURRENT_ISO_DATE = '2015-08-01 00:00:00';
    const ISO_DATE = '2015-08-03';
    const AMOUNT = 10;
    const CONCEPT = 'Pis';
    const ID = '531d52c5-d217-4a94-92f3-3e0f9b603a7a';

    /** @var Put */
    private $useCase;
    /** @var Source */
    private $source;

    protected function setUp()
    {
        $this->source = new Source();
        $this->useCase = new Put(
            $this->source,
            new DateTime(self::CURRENT_ISO_DATE)
        );

        $this->source->add(new Movement(
            Id::parse(self::ID),
            self::AMOUNT,
            self::CONCEPT,
            new DateTime(self::ISO_DATE),
            new DateTime(self::CURRENT_ISO_DATE)
        ));
    }

    /**
     * @test
     */
    public function ifMovementExistsShoulUpdateTheMovement()
    {
        $this->useCase->execute(
            self::ID,
            self::AMOUNT,
            self::CONCEPT,
            self::ISO_DATE
        );
        $movement = $this->source->byId(Movement\Id::parse(self::ID));

        $this->assertEquals(self::ID, $movement->id()->toString());
        $this->assertEquals(self::AMOUNT, $movement->amount());
        $this->assertEquals(self::CONCEPT, $movement->concept());
        $this->assertEquals(self::ISO_DATE, $movement->date()->isoDate());
        $this->assertEquals(self::CURRENT_ISO_DATE, $movement->created()->isoDateTime());
    }

    /**
     * @test
     */
    public function ifMovementDoesNotExistShoulUpdateTheMovement()
    {
        $newId = 'c1d74045-e24f-4adb-b707-c45dd86ffc19';
        $this->useCase->execute(
            $newId,
            self::AMOUNT,
            self::CONCEPT,
            self::ISO_DATE
        );
        $movement = $this->source->byId(Movement\Id::parse($newId));

        $this->assertInstanceOf('\kontuak\Movement', $movement);
    }
}