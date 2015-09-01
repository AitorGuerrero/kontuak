<?php

namespace kontuak\Tests\Interactors\CreateNewEntry;

use Kontuak\Implementation\InMemory\EntriesCollection;
use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Implementation\InMemory\MovementsSource;
use Kontuak\Interactors\CreateNewEntry\UseCase;
use Kontuak\Interactors\CreateNewEntry\Request;
use Kontuak\MovementId;

class CreateNewEntryTest extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var MovementsSource */
    private $source;
    /** @var int */
    private $amount = 10;
    /** @var string */
    private $concept = 'Pis';
    /** @var string */
    private $dateTimeSerialized = '2015-08-03';
    private $createdSerialized = '2015-08-01';
    /** @var \DateTime */
    private $created;

    protected function setUp()
    {
        $this->created = new \DateTime($this->createdSerialized);
        $this->request = new Request();
        $this->request->concept = $this->concept;
        $this->request->amount = $this->amount;
        $this->request->date = $this->dateTimeSerialized;
        $this->source = new MovementsSource();
        $this->useCase = new UseCase($this->source, $this->created);
    }

    /**
     * @test
     */
    public function whenTheAmountIsNegativeShouldSaveAsPositive()
    {
        $wrongAmount = -$this->amount;
        $this->request->amount = $wrongAmount;
        $response = $this->useCase->execute($this->request);
        $createdEntry = $this
            ->source
            ->collection()
            ->filterById(MovementId::fromString($response->entry['id']))
            ->first();

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
            ->getMockBuilder('Kontuak\Implementation\InMemory\MovementsSource')
            ->disableOriginalConstructor()
            ->getMock();
        $entriesCollection->method('add')->willThrowException(new \Exception());
        /** @var MovementsSource $entriesCollection */
        $useCase = new UseCase($entriesCollection, $this->created);
        $useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldSaveTheEntryCorrectly()
    {
        $response = $this->useCase->execute($this->request);
        $createdEntry = $this
            ->source
            ->collection()
            ->filterById(MovementId::fromString($response->entry['id']))
            ->first();

        $this->assertEquals($this->amount, $createdEntry->amount());
        $this->assertEquals($this->concept, $createdEntry->concept());
        $this->assertEquals($this->dateTimeSerialized, $createdEntry->date()->format('Y-m-d'));
        $this->assertEquals($this->createdSerialized, $createdEntry->created()->format('Y-m-d'));
    }
}