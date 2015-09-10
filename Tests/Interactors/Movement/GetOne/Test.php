<?php

namespace Interactors\Movement\GetOne;

use Kontuak\Interactors\Movement\GetOne\UseCase;
use Kontuak\Interactors\Movement\GetOne\Request;
use Kontuak\Implementation\Movement\Source;
use Kontuak\Movement;

class Test extends \PHPUnit_Framework_TestCase
{
    const INVALID_MOVEMENT_ID = 45;
    const MOVEMENT_ID_SERIALIZED = 'movement-id';
    /** @var Movement */
    private $movement;
    /** @var Source\InMemory */
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
        $movementId = Movement\Id::fromString(self::MOVEMENT_ID_SERIALIZED);
        $this->movement = new Movement(
            $movementId,
            self::AMOUNT,
            self::CONCEPT,
            new \DateTime(self::DATE_SERIALIZED),
            new \DateTime('2015-01-01 01:23:45')
        );
        $this->source = new Source\InMemory();
        $this->source->add($this->movement);
        $this->useCase = new UseCase($this->source);
        $this->request = new Request();
        $this->request->id = self::MOVEMENT_ID_SERIALIZED;
    }

    /**
     * @test
     */
    public function ifTheUserDoesNotExistsShowThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Interactors\Movement\GetOne\MovementNotFoundException');
        $this->request->id = self::INVALID_MOVEMENT_ID;
        $this->useCase->execute($this->request);
    }

    /**
     * @test
     */
    public function shouldReturnRequiredInfo()
    {
        $response = $this->useCase->execute($this->request);

        $this->assertEquals(self::MOVEMENT_ID_SERIALIZED, $response->movement['id']);
        $this->assertEquals(self::AMOUNT, $response->movement['amount']);
        $this->assertEquals(self::CONCEPT, $response->movement['concept']);
        $this->assertEquals(self::DATE_SERIALIZED, $response->movement['date']);
    }
}