<?php

namespace kontuak\Tests\Interactors\Movement\Create;

use Kontuak\Implementation\Movement\Source\InMemory as MovementSource;
use Kontuak\Implementation\PeriodicalMovement\Source\InMemory as PeriodicalMovementSource;
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
        $this->movementsGenerator = new MovementsGenerator(
            $this->source,
            new Movement\Id\Generator(),
            new \DateTime(self::CURRENT_ISO_DATE)
        );
        $this->useCase = new UseCase(
            $this->source,
            $this->periodicalMovementSource,
            $this->idGenerator,
            $this->movementsGenerator,
            new \DateTime(self::CURRENT_ISO_DATE)
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
        $thrownException = new \Exception();
        $entriesCollection->method('add')->willThrowException($thrownException);
        /** @var Movement\Source $entriesCollection */
        try {
            $useCase = new UseCase(
                $entriesCollection,
                $this->periodicalMovementSource,
                $this->idGenerator,
                $this->movementsGenerator,
                new \DateTime(self::CURRENT_ISO_DATE)
            );
            $useCase->execute($this->request);
        } catch (SystemException $e) {
            $this->assertEquals(
                'Persistence Layer failed',
                $e->getMessage()
            );
            $this->assertSame(
                $thrownException,
                $e->originalException()
            );
            throw $e;
        }
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
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(self::ID, $response->movementId);
        $this->assertEquals(self::AMOUNT, $response->movementAmount);
        $this->assertEquals(self::CONCEPT, $response->movementConcept);
        $this->assertEquals(self::ISO_DATE, $response->movementDate);
        $this->assertEquals(self::CURRENT_ISO_DATE, $response->movementCreated);
    }

    /**
     * @test
     */
    public function ifHasPeriodInformationShouldCreateAPeriodicalMovement()
    {
        $this->addPeriodInfoToRequest();
        $response = $this->useCase->execute($this->request);

        $periodicalMovement = $this->periodicalMovementSource->byId($response->periodicalMovementId);

        $this->assertEquals(3, $periodicalMovement->period()->amount());
        $this->assertEquals(Period::TYPE_MONTH_DAY, $periodicalMovement->period()->type());
    }

    /**
     * @test
     */
    public function ifHasPeriodInformationShouldGivePeriodicalMovementInfo()
    {
        $this->addPeriodInfoToRequest();
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, $response->periodicalMovementAmount);
        $this->assertEquals(Request::PERIOD_TYPE_MONTHS, $response->periodicalMovementType);
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

    /**
     * @test
     */
    public function whenIsPeriodicalTheMovementShouldBeLinkedToThePeriodicalMovement()
    {
        $this->addPeriodInfoToRequest();
        $response = $this->useCase->execute($this->request);

        $movement = $this->source->byId(new Movement\Id(self::ID));
        $this->assertNotNull($movement->periodicalMovement());
        $this->assertEquals($response->periodicalMovementId, $movement->periodicalMovement()->id()->serialize());
    }

    private function addPeriodInfoToRequest()
    {
        $this->request->isPeriodical = true;
        $this->request->periodType = Request::PERIOD_TYPE_MONTHS;
        $this->request->periodAmount = 3;
    }
}