<?php

namespace Ports\Movement\GetAll;

use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\IsoDateTime;
use Kontuak\Movement;
use Kontuak\Ports\Movement\GetAll;
use Kontuak\Ports\Movement\GetAll\UseCase;
use Kontuak\Ports\Movement\GetAll\Request;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Source */
    private $source;
    /** @var UseCase */
    public $useCase;
    /** @var Request */
    private $request;

    public function setUp()
    {
        $this->source = new Source();
        $this->useCase = new GetAll($this->source);
        $this->request = $this->useCase->newRequest();
        $this->request->limit = 20;
    }

    /**
     * @test
     */
    public function whenThereAreNoMovementsShouldReturnEmptyCollection()
    {
        $response = $this->useCase->execute($this->request);

        $this->assertInternalType('array', $response);
        $this->assertEquals(0, count($response));
    }

    /**
     * @test
     */
    public function whenNoLimitIsSettedShouldThrowAnException()
    {
        $this->setExpectedException('Kontuak\Ports\Exception\InvalidArgument');

        $request = $this->useCase->newRequest();
        $this->useCase = new GetAll($this->source);
        $this->useCase->execute($request);
    }

    /**
     * @test
     */
    public function whenLimitedShouldLimitTheResult()
    {
        $limit = 2;
        $this->generateMovements(3);
        $this->request->limit = $limit;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($limit, count($response));
    }

    /**
     * @test
     */
    public function whenPagedShouldReturnAskedPage()
    {
        $this->generateMovements(10);
        $this->request->limit = 10;
        $allMovements = $this->useCase->execute($this->request);

        $this->request->limit = 2;
        $this->request->page = 2;
        $pagedMovements = $this->useCase->execute($this->request);

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