<?php

namespace kontuak\Tests\Interactors\Movement\Create;

use Kontuak\Implementation\InMemory\Movement\Factory;
use Kontuak\Implementation\InMemory\PeriodicalMovement\Factory as PeriodicalMovementFactory;
use Kontuak\Implementation\InMemory\Movement\Source as MovementSource;
use Kontuak\Interactors\Movement\Create\UseCase;
use Kontuak\Interactors\Movement\Create\Request;
use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Movement;
use Kontuak\Period;

class Test extends \PHPUnit_Framework_TestCase
{
    const CURRENT_ISO_DATE = '2015-08-01 00:00:00';
    const ISO_DATE = '2015-08-03';
    const AMOUNT = 10;
    const CONCEPT = 'Pis';
    const ID = '531d52c5-d217-4a94-92f3-3e0f9b603a7a';
    private $periodicalMovementFactory;

    /** @var Factory */
    private $movementFactory;
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var MovementSource */
    private $source;

    protected function setUp()
    {
        $this->source = new MovementSource();
        $this->movementFactory = new Factory();
        $this->periodicalMovementFactory = new PeriodicalMovementFactory();
        $this->useCase = new UseCase(
            $this->source,
            new \DateTime(self::CURRENT_ISO_DATE),
            $this->movementFactory
        );
        
        $this->request = new Request();
        $this->request->id = self::ID;
        $this->request->concept = self::CONCEPT;
        $this->request->amount = self::AMOUNT;
        $this->request->date = self::ISO_DATE;
    }

    /**
     * @expectedException \Kontuak\Interactors\InvalidArgumentException
     * @test
     */
    public function whenConceptIsEmptyShouldThrowAnException()
    {
        try {
            $this->request->concept = '';
            $this->useCase->execute($this->request);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('"concept" should not be blank', $e->getMessage());
            throw $e;
        }
    }

    /**
     * @expectedException \Kontuak\Interactors\SystemException
     * @test
     */
    public function whenCollectionThrowsAnExceptionShouldThrowASystemException()
    {
        $movementsSource = $this
            ->getMockBuilder('Kontuak\Movement\Source')
            ->disableOriginalConstructor()
            ->getMock();
        $movementsSource->method('add')->willThrowException(new \Exception());
        /** @var Movement\Source $movementsSource */
        $useCase = new UseCase(
            $movementsSource,
            new \DateTime(self::CURRENT_ISO_DATE),
            $this->movementFactory
        );
        $useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldSaveTheMovement()
    {
        $this->useCase->execute($this->request);
        $movement = $this->source->byId(new Movement\Id(self::ID));

        $this->assertEquals(self::ID, $movement->id()->serialize());
        $this->assertEquals(self::AMOUNT, $movement->amount());
        $this->assertEquals(self::CONCEPT, $movement->concept());
        $this->assertEquals(self::ISO_DATE, $movement->date()->format('Y-m-d'));
        $this->assertEquals(self::CURRENT_ISO_DATE, $movement->created()->format('Y-m-d H:i:s'));
    }

}