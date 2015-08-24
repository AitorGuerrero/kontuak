<?php

namespace kontuak\Tests\Interactors\CreateNewEntry;

use Kontuak\Implementation\InMemory\EntityId;
use Kontuak\Implementation\InMemory\EntriesCollection;
use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Interactors\CreateNewEntry\UseCase;
use Kontuak\Interactors\CreateNewEntry\Request;

class CreateNewEntryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var EntriesCollection */
    private $entriesCollection;
    /** @var int */
    private $amount = 10;
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
        $this->dateTime = new \DateTime($this->dateTimeSerialized);
        $this->created = new \DateTime();
        $this->request = new Request($this->amount, $this->concept, $this->dateTime);
        $this->entriesCollection = new EntriesCollection(new MovementsCollection($this->created));
        $this->useCase = new UseCase($this->entriesCollection);
    }

    /**
     * @test
     */
    public function whenTheAmountIsNegativeShouldSaveAsPositive()
    {
        $wrongAmount = -$this->amount;
        $this->request->amount = $wrongAmount;
        $response = $this->useCase->execute($this->request);
        $createdEntry = $this->entriesCollection->find(new EntityId($response->entry['id']));

        $this->assertEquals($createdEntry->amount(), $this->amount);
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
        $entriesCollection = $this
            ->getMockBuilder('Kontuak\Implementation\InMemory\EntriesCollection')
            ->disableOriginalConstructor()
            ->getMock();
        $entriesCollection->method('add')->willThrowException(new \Exception());
        /** @var EntriesCollection $entriesCollection */
        $useCase = new UseCase($entriesCollection);
        $useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldSaveTheEntryCorrectly()
    {
        $response = $this->useCase->execute($this->request);
        $createdEntry = $this->entriesCollection->find(new EntityId($response->entry['id']));

        $this->assertEquals($this->amount, $createdEntry->amount());
        $this->assertEquals($this->concept, $createdEntry->concept());
        $this->assertEquals($this->dateTime, $createdEntry->date());
        $this->assertEquals($this->created, $createdEntry->created());
    }
}