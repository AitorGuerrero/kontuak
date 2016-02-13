<?php

namespace Ports\PeriodicalMovement\GetAll;

use Kontuak\Adapters\InMemory\PeriodicalMovement\Source;
use Kontuak\Adapters\Transformer\PeriodicalMovement;
use Kontuak\IsoDateTime;
use Kontuak\Ports\PeriodicalMovement\GetAll;
use Kontuak\Ports\PeriodicalMovement\GetAll\UseCase;
use Kontuak\Ports\PeriodicalMovement\GetAll\Request;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement\Id;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var Source */
    public $source;
    /** @var GetAll */
    public $useCase;
    /** @var Request */
    public $request;

    protected function setUp()
    {
        $this->source = new Source();
        $this->useCase = new GetAll($this->source);
        $this->request = $this->useCase->newRequest();
    }

    /**
     * @test
     */
    public function shouldReturnAllPeriodicalMovements()
    {
        $movementAId = '5df09125-7cc0-4686-af55-733765c04103';
        $movementBId = '5ee44e79-cbc1-4a50-8ba4-bc45026b5e87';
        $this->makeMovement($movementAId);
        $this->makeMovement($movementBId);

        $response = $this->useCase->execute($this->request);

        $this->assertEquals(2, count($response));
        $this->assertEquals($movementAId, $response[0]->id());
        $this->assertEquals($movementBId, $response[1]->id());
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

        $this->assertEquals(2, count($response));
    }

    /**
     * @param $id
     * @return mixed
     */
    private function makeMovement($id)
    {
        $movement = new \Kontuak\PeriodicalMovement(
            Id::parse($id),
            1,
            'concept',
            new DaysPeriod(2, new IsoDateTime('2015-01-01')),
            new \DateTimeImmutable('2015-01-01')
        );
        $this->source->add($movement);

        return $movement;
    }
}