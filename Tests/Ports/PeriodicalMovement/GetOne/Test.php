<?php

namespace Ports\PeriodicalMovement\GetOne;

use Kontuak\Adapters\InMemory\PeriodicalMovement\Source;
use Kontuak\Adapters\Transformer\PeriodicalMovement;
use Kontuak\IsoDateTime;
use Kontuak\Ports\PeriodicalMovement\GetOne;
use Kontuak\Ports\PeriodicalMovement\GetOne\UseCase;
use Kontuak\Ports\PeriodicalMovement\GetOne\Request;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement\Id;

class Test extends \PHPUnit_Framework_TestCase
{
    /** @var UseCase */
    public $useCase;
    /** @var Request */
    public $request;
    /** @var Source */
    public $source;

    protected function setUp()
    {
        $this->source = new Source();
        $this->useCase = new GetOne($this->source);
        $this->request = $this->useCase->newRequest();
    }

    /**
     * @test
     * @throws \Kontuak\Ports\Exception\EntityNotFound
     */
    public function whenPeriodicalMovementDoesNotExistsShouldThrowAnException()
    {
        $invalidId = '5df09125-7cc0-4686-af55-733765c04103';
        $this->setExpectedException('\Kontuak\Ports\Exception\EntityNotFound');
        $this->request->id = $invalidId;
        $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldReturnExpectedPeriodicalMovement()
    {
        $id = '5df09125-7cc0-4686-af55-733765c04103';
        $periodicalMovement = new \Kontuak\PeriodicalMovement(
            Id::parse($id),
            10,
            'concept',
            new DaysPeriod(3, new IsoDateTime('2015-01-01'))
        );
        $this->source->add($periodicalMovement);

        $this->request->id = $id;
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($id, $response->id());
    }
}