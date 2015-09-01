<?php

namespace Kontuak\Tests\Interactors\CreateNewEntry;

use Kontuak\Implementation\InMemory\ExpendituresCollection;
use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Implementation\InMemory\MovementsSource;
use Kontuak\Interactors\CreateNewExpenditure\UseCase;
use Kontuak\Interactors\CreateNewExpenditure\Request;
use Kontuak\MovementId;

class CreateNewExpenditureTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var MovementsSource */
    private $expendituresSource;
    /** @var int */
    private $amount = -10;
    /** @var string */
    private $concept = 'Pis';
    /** @var string */
    private $dateTimeSerialized = '2015-08-03';
    /** @var \DateTime */
    private $dateTime;
    /** @var \DateTime */
    private $created;

    protected function setUp()
    {
        $this->created = new \DateTime();
        $this->dateTime = new \DateTime($this->dateTimeSerialized);
        $this->request = new Request();
        $this->request->amount = $this->amount;
        $this->request->concept = $this->concept;
        $this->request->dateTimeSerialized = $this->dateTimeSerialized;
        $this->expendituresSource = new MovementsSource($this->created);
        $this->useCase = new UseCase($this->expendituresSource, $this->created);
    }

    /**
     * @test
     */
    public function whenTheAmountIsPositiveShouldSaveAsNegative()
    {
        $wrongAmount = -$this->amount;
        $this->request->amount = $wrongAmount;
        $response = $this->useCase->execute($this->request);
        $createdExpenditure = $this
            ->expendituresSource
            ->collection()
            ->filterById(MovementId::fromString(($response->expenditure['id'])))
            ->first();

        $this->assertEquals($createdExpenditure->amount(), $this->amount);
    }

    /**
     * @expectedException \Kontuak\Interactors\InvalidArgumentException
     * @test
     */
    public function whenConceptIsEmptyShouldThrowAnException()
    {
        $this->request->concept = '';
        $this->useCase->execute($this->request);
    }

    /**
     * @expectedException \Kontuak\Interactors\SystemException
     * @test
     */
    public function whenCollectionThrowsAnExceptionShouldThrowASystemException()
    {
        $expendituresSource = $this
            ->getMockBuilder('Kontuak\Implementation\InMemory\MovementsSource')
            ->disableOriginalConstructor()
            ->getMock();
        $expendituresSource->method('add')->willThrowException(new \Exception());
        /** @var MovementsSource $expendituresSource */
        $useCase = new UseCase($expendituresSource, $this->created);
        $useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldSaveTheExpenditureCorrectly()
    {
        $response = $this->useCase->execute($this->request);
        $createdExpenditure = $this->expendituresSource->collection()->filterById(
            MovementId::fromString($response->expenditure['id'])
        )->first();

        $this->assertEquals($this->amount, $createdExpenditure->amount());
        $this->assertEquals($this->concept, $createdExpenditure->concept());
        $this->assertEquals($this->dateTime, $createdExpenditure->date());
        $this->assertEquals($this->created, $createdExpenditure->created());
    }
}