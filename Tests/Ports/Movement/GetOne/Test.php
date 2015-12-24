<?php

namespace Ports\Movement\GetOne;

use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\IsoDateTime;
use Kontuak\Ports\Movement\GetOne;
use Kontuak\Ports\Movement\GetOne\Request;
use Kontuak\Movement;
use Kontuak\Ports\Movement\History;

class Test extends \PHPUnit_Framework_TestCase
{
    const INVALID_MOVEMENT_ID = '93f6c419-6822-4287-b84a-1cbfca6111f5';
    const MOVEMENT_ID_SERIALIZED = '082ce378-1736-495c-b821-e010294704ca';
    /** @var Movement */
    private $movement;
    /** @var Source */
    private $source;
    /** @var GetOne */
    private $useCase;
    const AMOUNT = 100;
    const CONCEPT = 'concept';
    const DATE_SERIALIZED = '2015-02-01';

    protected function setUp()
    {
        $movementId = Movement\Id::parse(self::MOVEMENT_ID_SERIALIZED);
        $this->movement = new Movement(
            $movementId,
            self::AMOUNT,
            self::CONCEPT,
            new IsoDateTime(self::DATE_SERIALIZED),
            new IsoDateTime('2015-01-01 01:23:45')
        );
        $this->source = new Source();
        $this->source->add($this->movement);
        $this->useCase = new GetOne($this->source);
    }

    /**
     * @test
     */
    public function ifTheUserDoesNotExistsShowThrowAnException()
    {
        $this->setExpectedException('\Kontuak\Ports\Exception\EntityNotFound');
        $this->useCase->execute(self::INVALID_MOVEMENT_ID);
    }

    /**
     * @test
     */
    public function shouldReturnRequiredInfo()
    {
        $movementResource = $this->useCase->execute(self::MOVEMENT_ID_SERIALIZED);

        $this->assertEquals(
            $this->movement->id()->toString(),
            $movementResource->id()
        );
    }
}