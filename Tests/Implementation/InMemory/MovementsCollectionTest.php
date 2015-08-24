<?php

namespace Kontuak\Tests\Implementation\InMemory;

use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Movement;

class MovementsCollectionTest extends \PHPUnit_Framework_TestCase
{
    use \Kontuak\Tests\Implementation\MovementsCollectionTest;

    protected function setUp()
    {
        $this->timeStamp = new \DateTime();
        $this->collection = new MovementsCollection($this->timeStamp);
    }

    /**
     * @test
     */
    public function filterByDateIs()
    {
        $movement1 = new Movement(1, 'pis', new \DateTime('2015-09-01'));
        $movement2 = new Movement(1, 'pis', new \DateTime('2015-09-02'));
        $movement3 = new Movement(1, 'pis', new \DateTime('2015-09-02'));
        $movement4 = new Movement(1, 'pis', new \DateTime('2015-09-05'));
        $this->collection->add($movement1);
        $this->collection->add($movement2);
        $this->collection->add($movement3);
        $this->collection->add($movement4);
        $movements = $this->collection->filterByDateIs(new \DateTime('2015-09-02'))->all();

        $this->assertEquals(2, count($movements));
    }
}