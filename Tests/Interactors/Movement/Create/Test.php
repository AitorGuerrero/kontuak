<?php

namespace kontuak\Tests\Interactors\Movement\Create;

use Kontuak\Implementation\InMemory\Movement\Factory;
use Kontuak\Implementation\InMemory\PeriodicalMovement\Factory as PeriodicalMovementFactory;
use Kontuak\Implementation\Movement\Source\InMemory as MovementSource;
use Kontuak\Implementation\PeriodicalMovement\Source\InMemory as PeriodicalMovementSource;
use Kontuak\Implementation\Transformer\Movement as MovementTransformer;
use Kontuak\Interactors\Movement\Create\UseCase;
use Kontuak\Interactors\Movement\Create\Request;
use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Interactors\SystemException;
use Kontuak\Movement;
use Kontuak\Period;
use Kontuak\PeriodicalMovement\Id\Generator;
use Kontuak\PeriodicalMovement\MovementsGenerator;

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
    /** @var MovementsGenerator */
    public $movementsGenerator;
    /** @var Generator */
    public $idGenerator;
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var MovementSource */
    private $source;
    /** @var PeriodicalMovementSource */
    private $periodicalMovementSource;

    protected function setUp()
    {
        $this->source = new MovementSource();
        $this->periodicalMovementSource = new PeriodicalMovementSource();
        $this->idGenerator = new Generator();
        $this->movementFactory = new Factory();
        $this->movementsGenerator = new MovementsGenerator(
            $this->source,
            new Movement\Id\Generator(),
            $this->movementFactory,
            new \DateTime(self::CURRENT_ISO_DATE)
        );
        $this->periodicalMovementFactory = new PeriodicalMovementFactory();
        $this->useCase = new UseCase(
            $this->source,
            $this->periodicalMovementSource,
            $this->idGenerator,
            $this->movementsGenerator,
            new \DateTime(self::CURRENT_ISO_DATE),
            new MovementTransformer(),
            $this->movementFactory,
            $this->periodicalMovementFactory
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
        $entriesCollection = $this
            ->getMockBuilder('Kontuak\Movement\Source')
            ->disableOriginalConstructor()
            ->getMock();
        $entriesCollection->method('add')->willThrowException(new \Exception());
        /** @var Movement\Source $entriesCollection */
        $useCase = new UseCase(
            $entriesCollection,
            $this->periodicalMovementSource,
            $this->idGenerator,
            $this->movementsGenerator,
            new \DateTime(self::CURRENT_ISO_DATE),
            new MovementTransformer(),
            $this->movementFactory,
            $this->periodicalMovementFactory
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

    /**
     * @test
     */
    public function shouldReturnMovementInfo()
    {
        /** @var Movement $movement */
        $movement = $this->useCase->execute($this->request);

        $this->assertEquals(self::ID, $movement->id()->serialize());
        $this->assertEquals(self::AMOUNT, $movement->amount());
        $this->assertEquals(self::CONCEPT, $movement->concept());
        $this->assertEquals(self::ISO_DATE, $movement->date()->format('Y-m-d'));
        $this->assertEquals(self::CURRENT_ISO_DATE, $movement->created()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function ifHasPeriodInformationShouldCreateAPeriodicalMovement()
    {
        /** @var Movement $movement */
        $this->addPeriodInfoToRequest();
        $movement = $this->useCase->execute($this->request);

        $this->assertEquals(3, $movement->periodicalMovement()->period()->amount());
        $this->assertEquals(Period::TYPE_MONTH_DAY, $movement->periodicalMovement()->period()->type());
    }

    /**
     * @test
     */
    public function evenItIsPeriodicalShouldCreateTheMovement()
    {
        $this->addPeriodInfoToRequest();
        $this->useCase->execute($this->request);

        $this->assertNotNull($this->source->byId(new Movement\Id(self::ID)));
    }

    private function addPeriodInfoToRequest()
    {
        $this->request->isPeriodical = true;
        $this->request->periodType = Request::PERIOD_TYPE_MONTHS;
        $this->request->periodAmount = 3;
    }
}