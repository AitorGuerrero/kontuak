<?php

namespace Kontuak\Tests\Ports\Movement\History;

use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\Ports\Movement\History\Request;
use Kontuak\Ports\Movement\History\UseCase;
use Kontuak\Movement;

/**
 * Class Test
 * @package Kontuak\Tests\Ports\Movement\History
 */
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
        $this->useCase = new UseCase(
            $this->source,
            $this->totalAmountService
        );
        $this->request = new Request();
        $this->request->limit = 5;
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
        $this->assertEquals($movement2->id()->toString(), $response->amounts[0]['movement']->id());
        $this->assertEquals($movement3->id()->toString(), $response->amounts[1]['movement']->id());
        $this->assertEquals($movement1->id()->toString(), $response->amounts[2]['movement']->id());
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
     * @test
     */
    public function whenLimitIsNullShouldNotLimit()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(100, '2015-08-05'));
        $this->source->add($this->generateMovement(-50, '2015-08-04'));
        $this->request->limit = null;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(3, count($response->amounts));
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
        return new Movement(
            Movement\Id::make(),
            $amount,
            $concept,
            new \DateTime($date),
            new \DateTime('2015-01-01')
        );
    }

}