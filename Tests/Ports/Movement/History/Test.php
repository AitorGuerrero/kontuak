<?php

namespace Kontuak\Tests\Ports\Movement\History;

use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\Ports\Movement\History;
use Kontuak\Ports\Movement\History\Request;
use Kontuak\Movement;

/**
 * Class Test
 * @package Kontuak\Tests\Ports\Movement\History
 */
class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Movement\Source */
    private $source;
    /** @var History */
    private $useCase;
    /** @var Movement\TotalAmountCalculator */
    private $totalAmountService;

    protected function setUp()
    {
        $this->source = new Source();
        $this->totalAmountService = new Movement\TotalAmountCalculator($this->source);
        $this->useCase = new History(
            $this->source,
            $this->totalAmountService
        );
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
        $response = $this->useCase->execute();

        $this->assertEquals(3, count($response));
        $this->assertEquals($movement2->id()->toString(), $response[0]['movement']->id());
        $this->assertEquals($movement3->id()->toString(), $response[1]['movement']->id());
        $this->assertEquals($movement1->id()->toString(), $response[2]['movement']->id());
    }

    /**
     * @test
     */
    public function shouldReturnTheMovementsTotalAmount()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(-40, '2015-08-05'));
        $response = $this->useCase->execute();

        $this->assertEquals(-10, $response[0]['totalAmount']);
        $this->assertEquals(30, $response[1]['totalAmount']);
    }

    /**
     * @test
     */
    public function whenThereAreMoreMovementsThanLimitShouldGetCorrectTotalAmount()
    {
        $fromIsoDate = null;
        $toIsoDate = null;
        $limit = 2;
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(100, '2015-08-05'));
        $this->source->add($this->generateMovement(-50, '2015-08-04'));
        $response = $this->useCase->execute($fromIsoDate, $toIsoDate, $limit);

        $this->assertEquals($limit, count($response));
    }

    /**
     * @test
     */
    public function whenLimitIsNullShouldNotLimit()
    {
        $this->source->add($this->generateMovement(30, '2015-08-01'));
        $this->source->add($this->generateMovement(100, '2015-08-05'));
        $this->source->add($this->generateMovement(-50, '2015-08-04'));
        $response = $this->useCase->execute();

        $this->assertEquals(3, count($response));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateFromShouldNotShowThatMovements()
    {
        $fromIsoDate = '2015-06-10';
        $this->source->add($this->generateMovement(10, '2015-01-10'));
        $response = $this->useCase->execute($fromIsoDate);

        $this->assertEquals(0, count($response));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateFromShouldNotShowThatDatesMovements()
    {
        $fromIsoDate = '2015-06-10';
        $this->source->add($this->generateMovement(10, '2015-06-10'));
        $response = $this->useCase->execute($fromIsoDate);

        $this->assertEquals(0, count($response));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateFromShouldShowPostDatesMovements()
    {
        $fromIsoDate = '2015-06-10';
        $this->source->add($this->generateMovement(10, '2015-06-11'));
        $response = $this->useCase->execute($fromIsoDate);

        $this->assertEquals(1, count($response));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateToShouldShowThatMovements()
    {
        $fromIsoDate = null;
        $toIsoDate = '2015-06-10';
        $this->source->add($this->generateMovement(10, '2015-01-10'));
        $response = $this->useCase->execute($fromIsoDate, $toIsoDate);

        $this->assertEquals(1, count($response));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateToShouldShowThatDatesMovements()
    {
        $fromIsoDate = '2015-06-10';
        $this->source->add($this->generateMovement(10, '2015-06-10'));
        $response = $this->useCase->execute(null, $fromIsoDate);

        $this->assertEquals(1, count($response));
    }

    /**
     * @test
     */
    public function whenAskedALimitDateToShouldNotShowPostDatesMovements()
    {
        $fromIsoDate = null;
        $toIsoDate = '2015-06-10';
        $this->source->add($this->generateMovement(10, '2015-08-11'));
        $response = $this->useCase->execute($fromIsoDate, $toIsoDate);

        $this->assertEquals(0, count($response));
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