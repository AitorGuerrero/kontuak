<?php

namespace Kontuak\Tests\Interactors\CreateNewEntry;

use Kontuak\Implementation\InMemory\EntityId;
use Kontuak\Implementation\InMemory\ExpendituresCollection;
use Kontuak\Interactors\CreateNewExpenditure\UseCase;
use Kontuak\Interactors\CreateNewExpenditure\Request;

class CreateNewExpenditureTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var ExpendituresCollection */
    private $expendituresCollection;
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
        $this->request = new Request($this->amount, $this->concept, $this->dateTimeSerialized);
        $this->expendituresCollection = new ExpendituresCollection($this->created);
        $this->useCase = new UseCase($this->expendituresCollection);
    }

    /**
     * @test
     */
    public function whenTheAmountIsPositiveShouldSaveAsNegative()
    {
        $wrongAmount = -$this->amount;
        $this->request->amount = $wrongAmount;
        $response = $this->useCase->execute($this->request);
        $createdExpenditure = $this->expendituresCollection->find(new EntityId($response->expenditure['id']));

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
        $expendituresCollection = $this
            ->getMockBuilder('Kontuak\Implementation\InMemory\ExpendituresCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $expendituresCollection->method('add')->willThrowException(new \Exception());
        /** @var ExpendituresCollection $expendituresCollection */
        $useCase = new UseCase($expendituresCollection);
        $useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldSaveTheExpenditureCorrectly()
    {
        $response = $this->useCase->execute($this->request);
        $createdExpenditure = $this->expendituresCollection->find(new EntityId($response->expenditure['id']));

        $this->assertEquals($this->amount, $createdExpenditure->amount());
        $this->assertEquals($this->concept, $createdExpenditure->concept());
        $this->assertEquals($this->dateTime, $createdExpenditure->date());
        $this->assertEquals($this->created, $createdExpenditure->created());
    }
}