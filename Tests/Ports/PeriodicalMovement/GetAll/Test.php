<?php

namespace Ports\PeriodicalMovement\GetAll;

use Kontuak\Adapters\InMemory\PeriodicalMovement\Factory;
use Kontuak\Adapters\InMemory\PeriodicalMovement\Source;
use Kontuak\Adapters\Transformer\PeriodicalMovement;
use Kontuak\Ports\PeriodicalMovement\GetAll\UseCase;
use Kontuak\Ports\PeriodicalMovement\GetAll\Request;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement\Id;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Source */
    public $source;
    /** @var UseCase */
    public $useCase;
    /** @var Request */
    public $request;
    /** @var Factory */
    public $factory;

    protected function setUp()
    {
        $this->factory = new Factory();
        $this->source = new Source();
        $this->useCase = new UseCase($this->source, new PeriodicalMovement());
        $this->request = $this->useCase->newRequest();
    }

    /**
     * @test
     */
    public function shouldReturnAllPeriodicalMovements()
    {
        $movementA = $this->makeMovement('5df09125-7cc0-4686-af55-733765c04103');
        $movementB = $this->makeMovement('5ee44e79-cbc1-4a50-8ba4-bc45026b5e87');

        $response = $this->useCase->execute($this->request);

        $this->assertEquals(2, count($response->periodicalMovements));
        $this->assertTrue(in_array($movementA, $response->periodicalMovements));
        $this->assertTrue(in_array($movementB, $response->periodicalMovements));
    }

    /**
     * @test
     */
    public function ifLimitedShouldReturnDesiredAmount()
    {
        $this->makeMovement('5df09125-7cc0-4686-af55-733765c04103');
        $this->makeMovement('5ee44e79-cbc1-4a50-8ba4-bc45026b5e87');
        $this->makeMovement('cf0056f1-1c9f-4361-b25c-7aa84a3542d9');

        $this->request->limit = 2;

        $response = $this->useCase->execute($this->request);

        $this->assertEquals(2, count($response->periodicalMovements));
    }

    /**
     * @param $id
     * @return mixed
     */
    private function makeMovement($id)
    {
        $movement = $this->factory->make(
            new Id($id),
            1,
            'concept',
            new \DateTime('2015-01-01'),
            new DaysPeriod(2)
        );
        $this->source->add($movement);

        return $movement;
    }
}