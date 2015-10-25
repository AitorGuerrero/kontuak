<?php

namespace Kontuak\Tests\Adapters\InMemory\Movement;

use Kontuak\Adapters\InMemory\Movement\Source;
use Kontuak\Adapters\InMemory\PeriodicalMovement\Factory;
use \Kontuak\PeriodicalMovement;
use \Kontuak\Movement;

class SourceTest extends \PHPUnit_Framework_TestCase
{
    use \Kontuak\Tests\Adapters\Movement\SourceTest;

    protected function setUp()
    {
        $this->source = new Source();
        $this->timeStamp = new \DateTime('2015-01-01');
        $this->periodicalMovementFactory = new Factory();
    }
}