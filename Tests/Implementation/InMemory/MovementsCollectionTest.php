<?php

namespace Kontuak\Tests\Implementation\InMemory;

use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\PeriodicalMovement;

class MovementsCollectionTest extends \PHPUnit_Framework_TestCase
{
    use \Kontuak\Tests\Implementation\MovementsCollectionTest;

    protected function setUp()
    {
        $this->timeStamp = new \DateTime();
        $this->collection = new MovementsCollection($this->timeStamp);
    }
}