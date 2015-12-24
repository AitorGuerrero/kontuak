<?php

namespace Kontuak\Tests;

use Kontuak\DateTime;
use Kontuak\Movement;

class MovementTest extends \PHPUnit_Framework_TestCase
{
    /** @var Movement */
    private $movement;

    protected function setUp()
    {
        $this->movement = new Movement(
            Movement\Id::make(),
            10,
            'Concept',
            new DateTime('2015-01-01'),
            new DateTime('2015-01-01')
        );
    }

    /**
     * @test
     */
    public function whenGiven0AmountShouldThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Movement\Exception\InvalidAmount');

        $this->movement->updateAmount(0);
        $this->movement->updateAmount('0');
        $this->movement->updateAmount(null);
        $this->movement->updateAmount(false);
    }

    /**
     * @test
     */
    public function whenGivenNanAmountShouldThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Movement\Exception\InvalidAmount');

        $this->movement->updateAmount('abc10');
        $this->movement->updateAmount([10]);
    }
}