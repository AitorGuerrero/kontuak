<?php

namespace Kontuak\Tests\Interactors\Movement\History;

use Kontuak\Implementation\InMemory\Movement\Factory;
use Kontuak\Implementation\InMemory\Movement\Source;
use Kontuak\Interactors\Movement\History\Request;
use Kontuak\Interactors\Movement\History\UseCase;
use Kontuak\Movement;

/**
 * Class Test
 * @package Kontuak\Tests\Interactors\Movement\History
 */
class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Factory */
    private $movementFactory;
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
        $this->useCase = new UseCase(
            $this->source,
            $this->totalAmountService,
            new \Kontuak\Implementation\Transformer\Movement()
        );
        $this->request = new Request();
        $this->request->limit = 5;
        $this->movementFactory = new Factory();
    }

    /**
     * @test
     */
    public function shouldReturnMovementsOrderedByDateDesc()
    {
        $movement1 = $this->generateMovement(30, '2015-08-01');
        $movement2 = $this->generateMovement(100, '2015-08-05');
        $movement3 = $this->generateMovement(-50, '2015-08-04');
        $this->source->add($movement1);
        $this->source->add($movement2);
        $this->source->add($movement3);
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, count($response->amounts));
        $this->assertEquals($movement2, $response->amounts[0]['movement']);
        $this->assertEquals($movement3, $response->amounts[1]['movement']);
        $this->assertEquals($movement1, $response->amounts[2]['movement']);
    }

    /**
     * @test
     */
    public function shouldReturnTheMovementsTotalAmount()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(-40, '2015-08-05'));
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(-10, $response->amounts[0]['totalAmount']);
        $this->assertEquals(30, $response->amounts[1]['totalAmount']);
    }

    /**
     * @test
     */
    public function whenThereAreMoreMovementsThanLimitShouldGetCorrectTotalAmount()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(100, '2015-08-05'));
        $this->source->add($this->generateMovement(-50, '2015-08-04'));
        $this->request->limit = 2;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(2, count($response->amounts));
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

    /**
     * @test
     */
    public function whenAskedALimitDateFromShouldNotShowThatMovements()
    {
        $this->source->add($this->generateMovement(10, '2015-01-10'));
        $this->request->fromDate = '2015-06-10';
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(0, count($response->amounts));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateFromShouldNotShowThatDatesMovements()
    {
        $this->source->add($this->generateMovement(10, '2015-06-10'));
        $this->request->fromDate = '2015-06-10';
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(0, count($response->amounts));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateFromShouldShowPostDatesMovements()
    {
        $this->source->add($this->generateMovement(10, '2015-06-11'));
        $this->request->fromDate = '2015-06-10';
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->amounts));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateToShouldShowThatMovements()
    {
        $this->source->add($this->generateMovement(10, '2015-01-10'));
        $this->request->toDate = '2015-06-10';
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->amounts));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateToShouldShowThatDatesMovements()
    {
        $this->source->add($this->generateMovement(10, '2015-06-10'));
        $this->request->toDate = '2015-06-10';
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(1, count($response->amounts));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateToShouldNotShowPostDatesMovements()
    {
        $this->source->add($this->generateMovement(10, '2015-08-11'));
        $this->request->toDate = '2015-06-10';
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(0, count($response->amounts));
    }

    public function generateMovement($amount = 10, $date = '2015-01-01', $concept = 'Concept')
    {
        return $this->movementFactory->make(
            $this->source->newId(),
            $amount,
            $concept,
            new \DateTime($date),
            new \DateTime('2015-01-01')
        );
    }

}