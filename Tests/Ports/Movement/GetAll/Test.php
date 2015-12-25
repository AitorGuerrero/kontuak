<?php

namespace Ports\Movement\GetAll;

use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\IsoDateTime;
use Kontuak\Movement;
use Kontuak\Ports\Movement\GetAll;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Source */
    private $source;
    /** @var GetAll */
    public $useCase;

    public function setUp()
    {
        $this->source = new Source();
        $this->useCase = new GetAll($this->source);
    }

    /**
     * @test
     */
    public function whenThereAreNoMovementsShouldReturnEmptyCollection()
    {
        $response = $this->useCase->execute(20, 1);

        $this->assertInternalType('array', $response);
        $this->assertEquals(0, count($response));
    }

    /**
     * @test
     */
    public function whenLimitedShouldLimitTheResult()
    {
        $limit = 2;
        $this->generateMovements(3);
        $response = $this->useCase->execute($limit, 1);

        $this->assertEquals($limit, count($response));
    }

    /**
     * @test
     */
    public function whenPagedShouldReturnAskedPage()
    {
        $this->generateMovements(10);
        $allMovements = $this->useCase->execute(10, 1);

        $pagedMovements = $this->useCase->execute(2, 2);

        $this->assertEquals($pagedMovements[0], $allMovements[2]);
        $this->assertEquals($pagedMovements[1], $allMovements[3]);
    }

    public function generateMovements($amount)
    {
        for ($i = 0; $i < $amount; $i++) {
            $this->generateMovement();
        }
    }

    public function generateMovement()
    {
        $movement = new Movement(
            Movement\Id::make(),
            1,
            'a',
            new IsoDateTime('2015-01-01'),
            new IsoDateTime('2015-01-01 00:00:00')
        );
        $this->source->add($movement);

        return $movement;
    }
}