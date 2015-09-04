<?php

namespace Kontuak\Tests\Interactors\Movement\History;

use Kontuak\Implementation\InMemory\Movement\Source;
use Kontuak\Interactors\Movement\History\Request;
use Kontuak\Interactors\Movement\History\UseCase;
use Kontuak\Movement;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Movement\Source */
    private $source;
    /** @var UseCase */
    private $useCase;
    /** @var Request */
    private $request;
    /** @var Movement\TotalAmountCalculator */
    private $totalAmountService;

    protected function setUp()
    {
        $this->source = new Source();
        $this->totalAmountService = new Movement\TotalAmountCalculator($this->source);
        $this->useCase = new UseCase($this->source, $this->totalAmountService);
        $this->request = new Request();
        $this->request->limit = 5;
    }

    /**
     * @test
     *
     */
    public function shouldReturnMovementsOrderedByDateDesc()
    {
        $movement1 = new Movement(new Movement\Id(), 30, 'A', new \DateTime('2015-08-01'), new \DateTime('2015-01-01'));
        $movement2 = new Movement(new Movement\Id(), 100, 'B', new \DateTime('2015-08-05'), new \DateTime('2015-01-01'));
        $movement3 = new Movement(new Movement\Id(), -50, 'C', new \DateTime('2015-08-04'), new \DateTime('2015-01-01'));
        $this->source->add($movement1);
        $this->source->add($movement2);
        $this->source->add($movement3);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, count($response->movements));
        $this->assertEquals($movement2->id()->serialize(), $response->movements[0]['id']);
        $this->assertEquals($movement3->id()->serialize(), $response->movements[1]['id']);
        $this->assertEquals($movement1->id()->serialize(), $response->movements[2]['id']);
    }

    /**
     * @test
     */
    public function shouldReturnMovementsMainData()
    {
        $amount = 30;
        $concept = 'A';
        $date = '2015-08-01';
        $entry1 = new Movement(new Movement\Id(), $amount, $concept, new \DateTime($date), new \DateTime('2015-01-01'));
        $this->source->add($entry1);
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
        $this->source->add(new Movement(new Movement\Id(), 30, 'A', new \DateTime('2015-08-01'), new \DateTime('2015-01-01')));
        $this->source->add(new Movement(new Movement\Id(), 100, 'B', new \DateTime('2015-08-05'), new \DateTime('2015-01-01')));
        $this->source->add(new Movement(new Movement\Id(), -50, 'C', new \DateTime('2015-08-04'), new \DateTime('2015-01-01')));
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(80, $response->movements[0]['totalAmount']);
        $this->assertEquals(-20, $response->movements[1]['totalAmount']);
        $this->assertEquals(30, $response->movements[2]['totalAmount']);
    }

    /**
     * @test
     */
    public function whenThereAreMoreMovementsThanLimitShouldGetCorrectTotalAmount()
    {
        $this->source->add(new Movement(new Movement\Id(), 30, 'A', new \DateTime('2015-08-01'), new \DateTime('2015-01-01')));
        $this->source->add(new Movement(new Movement\Id(), 100, 'B', new \DateTime('2015-08-05'), new \DateTime('2015-01-01')));
        $this->source->add(new Movement(new Movement\Id(), -50, 'C', new \DateTime('2015-08-04'), new \DateTime('2015-01-01')));
        $this->request->limit = 2;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(80, $response->movements[0]['totalAmount']);
        $this->assertEquals(-20, $response->movements[1]['totalAmount']);
    }

    /**
     * @test
     */
    public function shouldReturnRequiredData()
    {
        $movementId = new Movement\Id();
        $amount = -50;
        $concept = 'C';
        $date = new \DateTime('2015-08-04');
        $created = new \DateTime('2015-01-01');
        $this->source->add(new Movement($movementId, $amount, $concept, $date, $created));
        $this->request->limit = 2;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($movementId->serialize(), $response->movements[0]['id']);
        $this->assertEquals($amount, $response->movements[0]['amount']);
        $this->assertEquals($concept, $response->movements[0]['concept']);
        $this->assertEquals($date->format('Y-m-d'), $response->movements[0]['date']);
        $this->assertEquals($created->format('Y-m-d h:i:s'), $response->movements[0]['created']);
        $this->assertEquals(-50, $response->movements[0]['totalAmount']);
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