<?php

namespace Interactors\Movement\GetAll;

use Kontuak\Implementation\InMemory\Movement\Factory;
use Kontuak\Implementation\InMemory\Movement\Source;
use Kontuak\Interactors\Movement\GetAll\UseCase;
use Kontuak\Interactors\Movement\GetAll\Request;
use Kontuak\Movement\Id\Generator;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Source */
    private $source;
    /** @var UseCase */
    public $useCase;
    /** @var Request */
    private $request;
    /** @var Factory */
    private $factory;

    public function setUp()
    {
        $this->factory = new Factory();
        $this->source = new Source();
        $this->useCase = new UseCase($this->source);
        $this->request = $this->useCase->newRequest();
        $this->request->limit = 20;
    }

    /**
     * @test
     */
    public function whenThereAreNoMovementsShouldReturnEmptyCollection()
    {
        $response = $this->useCase->execute($this->request);

        $this->assertInternalType('array', $response->movements);
        $this->assertEquals(0, count($response->movements));
    }

    /**
     * @test
     */
    public function whenNoLimitIsSettedShouldThrowAnException()
    {
        $this->setExpectedException('Kontuak\Interactors\Exception\InvalidArgument');

        $request = $this->useCase->newRequest();
        $this->useCase = new UseCase($this->source);
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

        $this->assertEquals($limit, count($response->movements));
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

        $this->assertEquals($pagedMovements->movements[0], $allMovements->movements[2]);
        $this->assertEquals($pagedMovements->movements[1], $allMovements->movements[3]);
    }

    public function generateMovements($amount)
    {
        for ($i = 0; $i < $amount; $i++) {
            $this->generateMovement();
        }
    }

    public function generateMovement()
    {
        $movement = $this->factory->make(
            $this->source->newId(),
            1,
            'a',
            new \DateTime('2015-01-01'),
            new \DateTime('2015-01-01 00:00:00')
        );
        $this->source->add($movement);

        return $movement;
    }
}