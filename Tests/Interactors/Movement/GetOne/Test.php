<?php

namespace Interactors\Movement\GetOne;

use Kontuak\Adapters\InMemory\Movement\Factory;
use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\Interactors\Movement\GetOne\UseCase;
use Kontuak\Interactors\Movement\GetOne\Request;
use Kontuak\Movement;

class Test extends \PHPUnit_Framework_TestCase
{
    const INVALID_MOVEMENT_ID = '93f6c419-6822-4287-b84a-1cbfca6111f5';
    const MOVEMENT_ID_SERIALIZED = '082ce378-1736-495c-b821-e010294704ca';
    /** @var Movement */
    private $movement;
    /** @var Source */
    private $source;
    /** @var UseCase */
    private $useCase;
    /** @var Request */
    private $request;
    const AMOUNT = 100;
    const CONCEPT = 'concept';
    const DATE_SERIALIZED = '2015-02-01';

    protected function setUp()
    {
        $movementFactory = new Factory();
        $movementId = new Movement\Id(self::MOVEMENT_ID_SERIALIZED);
        $this->movement = $movementFactory->make(
            $movementId,
            self::AMOUNT,
            self::CONCEPT,
            new \DateTime(self::DATE_SERIALIZED),
            new \DateTime('2015-01-01 01:23:45')
        );
        $this->source = new Source();
        $this->source->add($this->movement);
        $this->useCase = new UseCase($this->source, new \Kontuak\Adapters\Transformer\Movement());
        $this->request = new Request();
        $this->request->id = self::MOVEMENT_ID_SERIALIZED;
    }

    /**
     * @test
     */
    public function ifTheUserDoesNotExistsShowThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Interactors\Exception\EntityNotFound');
        $this->request->id = self::INVALID_MOVEMENT_ID;
        $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldReturnRequiredInfo()
    {
        $response = $this->useCase->execute($this->request);

        $this->assertEquals($this->movement, $response->movement);
    }
}