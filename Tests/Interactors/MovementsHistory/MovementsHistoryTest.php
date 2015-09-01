<?php

namespace Kontuak\Tests\Interactors\MovementsHistory;

use Kontuak\Entry;
use Kontuak\Expenditure;
use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Interactors\MovementsHistory\Request;
use Kontuak\Interactors\MovementsHistory\UseCase;
use Kontuak\Movement;
use Kontuak\MovementId;
use Kontuak\MovementsCollection\TotalAmount;

class MovementsHistoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var MovementsCollection */
    private $collection;
    /** @var UseCase */
    private $useCase;
    /** @var Request */
    private $request;
    /** @var TotalAmount */
    private $totalAmountService;

    protected function setUp()
    {
        $timeStamp = new \DateTime();
        $this->collection = new MovementsCollection($timeStamp);
        $this->totalAmountService = new TotalAmount($this->collection);
        $this->useCase = new UseCase($this->collection, $this->totalAmountService);
        $this->request = new Request();
        $this->request->limit = 5;
    }

    /**
     * @test
     *
     */
    public function shouldReturnMovementsOrderedByDateDesc()
    {
        $entry1 = new Movement(new MovementId(), 30, 'A', new \DateTime('2015-08-01'));
        $entry2 = new Movement(new MovementId(), 100, 'B', new \DateTime('2015-08-05'));
        $expenditure1 = new Movement(new MovementId(), -50, 'C', new \DateTime('2015-08-04'));
        $this->collection->add($entry1);
        $this->collection->add($entry2);
        $this->collection->add($expenditure1);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, count($response->movements));
        $this->assertEquals($entry1->id()->serialize(), $response->movements[0]['id']);
        $this->assertEquals($expenditure1->id()->serialize(), $response->movements[1]['id']);
        $this->assertEquals($entry2->id()->serialize(), $response->movements[2]['id']);
    }

    /**
     * @test
     */
    public function shouldReturnMovementsMainData()
    {
        $amount = 30;
        $concept = 'A';
        $date = '2015-08-01';
        $entry1 = new Movement(new MovementId(), $amount, $concept, new \DateTime($date));
        $this->collection->add($entry1);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($amount, $response->movements[0]['amount']);
        $this->assertEquals($concept, $response->movements[0]['concept']);
        $this->assertEquals($date, $response->movements[0]['date']);
    }

    /**
     * @test
     */
    public function shouldReturnTheMovementsTotalAmount()
    {
        $this->collection->add(new Movement(new MovementId(), 30, 'A', new \DateTime('2015-08-01')));
        $this->collection->add(new Movement(new MovementId(), 100, 'B', new \DateTime('2015-08-05')));
        $this->collection->add(new Movement(new MovementId(), -50, 'C', new \DateTime('2015-08-04')));
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(30, $response->movements[0]['totalAmount']);
        $this->assertEquals(-20, $response->movements[1]['totalAmount']);
        $this->assertEquals(80, $response->movements[2]['totalAmount']);
    }

    /**
     * @test
     */
    public function whenThereAreMoreMovementsThanLimitShouldGetCorrectTotalAmount()
    {
        $this->collection->add(new Movement(new MovementId(), 30, 'A', new \DateTime('2015-08-01')));
        $this->collection->add(new Movement(new MovementId(), 100, 'B', new \DateTime('2015-08-05')));
        $this->collection->add(new Movement(new MovementId(), -50, 'C', new \DateTime('2015-08-04')));
        $this->request->limit = 2;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(-20, $response->movements[0]['totalAmount']);
        $this->assertEquals(80, $response->movements[1]['totalAmount']);
    }

    /**
     * @expectedException \Kontuak\Interactors\InvalidArgumentException
     * @test
     */
    public function whenLimitIsNotANumberShouldThrowAnException()
    {
        $this->request->limit = null;
        $this->useCase->execute($this->request);
    }

}