<?php

namespace Kontuak\Tests\Movement;

use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\Movement;
use Kontuak\Movement\TotalAmountCalculator;

class TotalAmountCalculatorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldNotIncludeMovementsAmount()
    {
        $source = new Source();
        $movement = $this->movementGenerator('2015-01-01', 1);
        $source->add($movement);
        $calculator = new TotalAmountCalculator($source);

        $this->assertEquals(0, $calculator->getForAMovement($movement));
    }

    /**
     * @test
     */
    public function shouldNotIncludePosteriorMovements()
    {
        $source = new Source();
        $movement = $this->movementGenerator('2015-01-01', 1);
        $source->add($movement);
        $source->add($this->movementGenerator('2015-01-02', 1));
        $calculator = new TotalAmountCalculator($source);

        $this->assertEquals(0, $calculator->getForAMovement($movement));
    }

    /**
     * @test
     */
    public function shouldIncludePreviusMovements()
    {
        $source = new Source();
        $movement = $this->movementGenerator('2015-01-01', 1);
        $source->add($movement);
        $source->add($this->movementGenerator('2014-01-01', 1));
        $calculator = new TotalAmountCalculator($source);

        $this->assertEquals(1, $calculator->getForAMovement($movement));
    }

    private function movementGenerator($isoDate = '2015-06-01', $amount = 10)
    {
        return new Movement(
            new Movement\Id(uniqid()),
            $amount,
            'Concept',
            new \DateTime($isoDate),
            new \DateTime('2015-01-01')
        );
    }
}