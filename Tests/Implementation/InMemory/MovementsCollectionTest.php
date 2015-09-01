<?php

namespace Kontuak\Tests\Implementation\InMemory;

use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Implementation\InMemory\MovementsSource;
use Kontuak\PeriodicalMovement;

class MovementsCollectionTest extends \PHPUnit_Framework_TestCase
{
    use \Kontuak\Tests\Implementation\MovementsCollectionTest;

    private $collection;

    protected function setUp()
    {
        $this->created = new \DateTime('2015-01-01');
        $this->timeStamp = new \DateTime();
        $this->dataSource = new MovementsSource();
        $this->collection = new MovementsCollection($this->dataSource);
    }
}