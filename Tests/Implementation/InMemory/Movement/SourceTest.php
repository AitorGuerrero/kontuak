<?php

namespace Kontuak\Tests\Implementation\InMemory\Movement;

use Kontuak\Implementation\InMemory\PeriodicalMovement\Factory;
use Kontuak\Implementation\Movement\Source;
use \Kontuak\PeriodicalMovement;
use \Kontuak\Movement;

class SourceTest extends \PHPUnit_Framework_TestCase
{
    use \Kontuak\Tests\Implementation\Movement\SourceTest;

    protected function setUp()
    {
        $this->idGenerator = new Movement\Id\Generator();
        $this->source = new Source\InMemory();
        $this->timeStamp = new \DateTime('2015-01-01');
        $this->idGenerator = new Movement\Id\Generator();
        $this->periodicalMovementFactory = new Factory();
        $this->periodicalMovementIdGenerator = new PeriodicalMovement\Id\Generator();
    }
}