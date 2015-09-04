<?php

namespace kontuak\Tests\Interactors\Movement\Create;

use Kontuak\Implementation\InMemory\Movement as InMemoryMovement;
use Kontuak\Interactors\CreateNewEntry\UseCase;
use Kontuak\Interactors\CreateNewEntry\Request;
use Kontuak\Movement;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Request */
    private $request;
    /** @var UseCase */
    private $useCase;
    /** @var InMemoryMovement\Source */
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
        $this->source = new InMemoryMovement\Source();
        $this->useCase = new UseCase($this->source, $this->created);
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
            ->getMockBuilder('Kontuak\Movement\Source')
            ->disableOriginalConstructor()
            ->getMock();
        $entriesCollection->method('add')->willThrowException(new \Exception());
        /** @var Movement\Source $entriesCollection */
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
            ->findById(Movement\Id::fromString($response->entry['id']));

        $this->assertEquals($this->amount, $createdEntry->amount());
        $this->assertEquals($this->concept, $createdEntry->concept());
        $this->assertEquals($this->dateTimeSerialized, $createdEntry->date()->format('Y-m-d'));
        $this->assertEquals($this->createdSerialized, $createdEntry->created()->format('Y-m-d'));
    }
}