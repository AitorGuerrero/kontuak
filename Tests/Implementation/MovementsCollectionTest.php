<?php

namespace Kontuak\Tests\Implementation;

use Kontuak\EntityId;
use Kontuak\Movement;
use Kontuak\MovementId;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement;

/**
 * A trait for apply in the MovementsCollection implementations tests.
 * To use this, you should define at the setUp method the property $collection with your implementation of
 * MovementsCollection, and the $timeStamp property with the current timeStamp.
 *
 * Class MovementsCollectionTest
 * @package Kontuak\Tests\Implementation
 */
trait MovementsCollectionTest
{
    /** @var \Kontuak\MovementsCollection */
    protected $collection;
    /** @var \DateTimeInterface */
    protected $timeStamp;

    /**
     * @test
     */
    public function whenAddedMovementShouldBeIdentified()
    {
        $movement = new Movement(
            new MovementId(),
            1,
            'pis',
            new \DateTime('2015-09-01')
        );
        $this->collection->add($movement);

        $this->assertTrue($movement->id() instanceof MovementId);
    }

    /**
     * @test
     */
    public function whenAddedMovementShouldHaveACreationDate()
    {
        $movement = new Movement(
            new MovementId(),
            1,
            'pis',
            new \DateTime('2015-09-01')
        );
        $this->collection->add($movement);

        $this->assertEquals($this->timeStamp, $movement->created());
    }

    /**
     * @test
     */
    public function whenDefinedAOrderedByDateDescShouldReturnElementsInThatOrder()
    {
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-04'));
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-02'));
        $this->collection->add($movement1);
        $this->collection->add($movement2);
        $this->collection->add($movement3);
        $this->collection->orderByDateDesc();
        $movements = $this->collection->all();

        $this->assertEquals($movements[0]->id()->serialize(), $movement2->id()->serialize());
        $this->assertEquals($movements[1]->id()->serialize(), $movement3->id()->serialize());
        $this->assertEquals($movements[2]->id()->serialize(), $movement1->id()->serialize());
    }

    /**
     * @test
     */
    public function whenSettedALimitShouldLimitTheResults()
    {
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $this->collection->add($movement1);
        $this->collection->add($movement2);
        $this->collection->add($movement3);
        $this->collection->limit(2);
        $results = $this->collection->all();

        $this->assertEquals(2, count($results));
    }

    /**
     * @test
     */
    public function byDefaultShouldOrderByCreationDate()
    {
        $movement1 = new Movement(new MovementId(), 1, 'mov1', new \DateTime('2015-09-02'));
        $movement2 = new Movement(new MovementId(), 1, 'mov2', new \DateTime('2015-09-05'));
        $movement3 = new Movement(new MovementId(), 1, 'mov3', new \DateTime('2015-09-01'));
        $this->collection->add($movement1);
        $this->collection->add($movement2);
        $this->collection->add($movement3);
        $results = $this->collection->all();

        $this->assertEquals($movement1->id()->serialize(), $results[0]->id()->serialize());
        $this->assertEquals($movement2->id()->serialize(), $results[1]->id()->serialize());
        $this->assertEquals($movement3->id()->serialize(), $results[2]->id()->serialize());
    }

    /**
     * @test
     */
    public function filterDateLessThan()
    {
        $date = new \DateTime('2015-09-05');
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-05'));
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-07'));
        $this->collection->add($movement1);
        $this->collection->add($movement2);
        $this->collection->add($movement3);
        $movements = $this->collection->filterDateLessThan($date)->all();

        $this->assertEquals(1, count($movements));
        $this->assertEquals($movement2->id()->serialize(), $movements[0]->id()->serialize());
    }

    /**
     * @test
     */
    public function amountSum()
    {
        $this->collection->add(new Movement(new MovementId(), 20, 'pis', new \DateTime('2015-09-05')));
        $this->collection->add(new Movement(new MovementId(), 30, 'pis', new \DateTime('2015-09-05')));

        $this->assertEquals(50, $this->collection->amountSum());
    }

    /**
     * @test
     */
    public function filterByCreatedIsLessThan()
    {
        $date = new \DateTime('2015-09-05 15:20:00');
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $this->collection->add($movement1);
        $this->collection->add($movement2);
        $this->collection->add($movement3);
        $movement1->setCreated(new \DateTime('2015-09-04 00:00:00'));
        $movement2->setCreated(new \DateTime('2015-09-05 15:20:00'));
        $movement3->setCreated(new \DateTime('2015-09-06 00:00:00'));
        $movements = $this->collection->filterByCreatedIsLessThan($date)->all();

        $this->assertEquals(1, count($movements));
        $this->assertEquals($movement1->id()->serialize(), $movements[0]->id()->serialize());
    }

    /**
     * @test
     */
    public function filterByDateIs()
    {
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'));
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-02'));
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-02'));
        $movement4 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-05'));
        $this->collection->add($movement1);
        $this->collection->add($movement2);
        $this->collection->add($movement3);
        $this->collection->add($movement4);
        $movements = $this->collection->filterByDateIs(new \DateTime('2015-09-02'))->all();

        $this->assertEquals(2, count($movements));
    }

    /**
     * @test
     */
    public function filterByOwnedByPeriodicalMovement()
    {
        $periodicalMovement = new PeriodicalMovement(1, 'a', new \DateTime(), new DaysPeriod(1));
        $movement1 = Movement::fromPeriodicalMovement($periodicalMovement, new \DateTime());
        $movement2 = new Movement(new MovementId(), 2, 'b', new \DateTime());

        $this->collection->add($movement1);
        $this->collection->add($movement2);

        $movements = $this
            ->collection
            ->filterByPeriodicalMovement($periodicalMovement)
            ->all();

        $this->assertEquals(1, count($movements));
        $this->assertEquals($periodicalMovement, $movements[0]->periodicalMovement());
    }

    /**
     * @test
     */
    public function first()
    {
        $movement1 = new Movement(new MovementId(), 1, 'a', new \DateTime());
        $movement2 = new Movement(new MovementId(), 2, 'b', new \DateTime());

        $this->collection->add($movement1);
        $this->collection->add($movement2);

        $movement = $this->collection->first();

        $this->assertEquals($movement1, $movement);
    }
}