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

class Test extends \PHPUnit_Framework_TestCase
{
    public $generator;
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var MovementSource */
    private $source;
    /** @var int */
    private $amount = 10;
    /** @var string */
    private $concept = 'Pis';
    /** @var string */
    private $dateTimeSerialized = '2015-08-03';
    private $createdSerialized = '2015-08-01 00:00:00';
    /** @var \DateTime */
    private $created;
    private $id = '531d52c5-d217-4a94-92f3-3e0f9b603a7a';
    /** @var PeriodicalMovementSource */
    private $periodicalMovementSource;

    protected function setUp()
    {
        $this->periodicalMovementSource = new PeriodicalMovementSource();
        $this->created = new \DateTime($this->createdSerialized);
        $this->request = new Request();
        $this->request->id = $this->id;
        $this->request->concept = $this->concept;
        $this->request->amount = $this->amount;
        $this->request->date = $this->dateTimeSerialized;
        $this->source = new MovementSource();
        $this->generator = new Generator();
        $this->useCase = new UseCase(
            $this->source,
            $this->periodicalMovementSource,
            $this->generator,
            $this->created
        );
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
                $this->generator,
                $this->created
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
        $movement = $this->source->byId(new Movement\Id($this->id));

        $this->assertEquals($this->id, $movement->id()->serialize());
        $this->assertEquals($this->amount, $movement->amount());
        $this->assertEquals($this->concept, $movement->concept());
        $this->assertEquals($this->dateTimeSerialized, $movement->date()->format('Y-m-d'));
        $this->assertEquals($this->createdSerialized, $movement->created()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function shouldReturnMovementInfo()
    {
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($this->id, $response->movementId);
        $this->assertEquals($this->amount, $response->movementAmount);
        $this->assertEquals($this->concept, $response->movementConcept);
        $this->assertEquals($this->dateTimeSerialized, $response->movementDate);
        $this->assertEquals($this->createdSerialized, $response->movementCreated);
    }

    /**
     * @test
     */
    public function ifHasPeriodInformationShouldCreateAPeriodicalMovement()
    {
        $this->request->isPeriodical = true;
        $this->request->periodType = Request::PERIOD_TYPE_MONTHS;
        $this->request->periodAmount = 3;
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
        $this->request->isPeriodical = true;
        $this->request->periodType = Request::PERIOD_TYPE_MONTHS;
        $this->request->periodAmount = 3;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, $response->periodicalMovementAmount);
        $this->assertEquals(Request::PERIOD_TYPE_MONTHS, $response->periodicalMovementType);
    }
}