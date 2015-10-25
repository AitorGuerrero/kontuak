<?php

namespace Ports\Movement\Put;

use Kontuak\Ports\Movement\Put\Request;
use Kontuak\Ports\Movement\Put\UseCase;
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

    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var Source */
    private $source;

    protected function setUp()
    {
        $this->source = new Source();
        $this->useCase = new UseCase(
            $this->source,
            new \Kontuak\Adapters\Transformer\Movement(),
            new \DateTime(self::CURRENT_ISO_DATE)
        );

        $this->source->add(new Movement(
            Id::parse(self::ID),
            self::AMOUNT,
            self::CONCEPT,
            new \DateTime(self::ISO_DATE),
            new \DateTime(self::CURRENT_ISO_DATE)
        ));

        $this->request = new Request(
            self::ID,
            self::CONCEPT,
            self::AMOUNT,
            self::ISO_DATE
        );
    }

    /**
     * @test
     */
    public function whenMovementDoesNotExistsShouldReturnCreated()
    {
        $response = $this->useCase->execute(new Request(
            'd7ae6ce8-589f-4b17-97e7-26151cdfc9dc',
            self::CONCEPT,
            self::AMOUNT,
            self::ISO_DATE
        ));
        $this->assertTrue($response->isNew());
    }

    /**
     * @test
     */
    public function whenMovementExistsShouldReturnNotCreated()
    {
        $response = $this->useCase->execute(new Request(
            self::ID,
            self::CONCEPT,
            self::AMOUNT,
            self::ISO_DATE
        ));
        $this->assertFalse($response->isNew());
    }

    /**
     * @test
     */
    public function ifMovementExistsShoulUpdateTheMovement()
    {
        $this->useCase->execute($this->request);
        $movement = $this->source->byId(Movement\Id::parse(self::ID));

        $this->assertEquals(self::ID, $movement->id()->toString());
        $this->assertEquals(self::AMOUNT, $movement->amount());
        $this->assertEquals(self::CONCEPT, $movement->concept());
        $this->assertEquals(self::ISO_DATE, $movement->date()->format('Y-m-d'));
        $this->assertEquals(self::CURRENT_ISO_DATE, $movement->created()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function ifMovementDoesNotExistShoulUpdateTheMovement()
    {
        $newId = 'c1d74045-e24f-4adb-b707-c45dd86ffc19';
        $this->useCase->execute(new Request(
            $newId,
            self::CONCEPT,
            self::AMOUNT,
            self::ISO_DATE
        ));
        $movement = $this->source->byId(Movement\Id::parse($newId));

        $this->assertInstanceOf('\kontuak\Movement', $movement);
    }
}