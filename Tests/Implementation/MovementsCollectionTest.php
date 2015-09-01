<?php

namespace Kontuak\Tests\Implementation;

use Kontuak\Movement;
use Kontuak\MovementId;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovementId;

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
    /** @var \DateTime */
    private $created;
    /** @var \Kontuak\MovementsSource */
    protected $dataSource;
    /** @var \DateTimeInterface */
    protected $timeStamp;


    /**
     * @test
     */
    public function whenDefinedAOrderedByDateDescShouldReturnElementsInThatOrder()
    {
        $this->created = new \DateTime('2015-01-01');
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), $this->created);
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-04'), $this->created);
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-02'), $this->created);
        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);
        $this->dataSource->add($movement3);
        $movements = $this->dataSource->collection()->orderByDateDesc()->toArray();

        $this->assertEquals($movements[0]->id()->serialize(), $movement2->id()->serialize());
        $this->assertEquals($movements[1]->id()->serialize(), $movement3->id()->serialize());
        $this->assertEquals($movements[2]->id()->serialize(), $movement1->id()->serialize());
    }

    /**
     * @test
     */
    public function whenSettedALimitShouldLimitTheResults()
    {
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), $this->created);
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), $this->created);
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), $this->created);
        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);
        $this->dataSource->add($movement3);
        $results = $this->dataSource->collection()->limit(2)->toArray();

        $this->assertEquals(2, count($results));
    }

    /**
     * @test
     */
    public function byDefaultShouldOrderByCreationDate()
    {
        $movement1 = new Movement(new MovementId(), 1, 'mov1', new \DateTime('2015-09-02'), $this->created);
        $movement2 = new Movement(new MovementId(), 1, 'mov2', new \DateTime('2015-09-05'), $this->created);
        $movement3 = new Movement(new MovementId(), 1, 'mov3', new \DateTime('2015-09-01'), $this->created);
        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);
        $this->dataSource->add($movement3);
        $results = $this->dataSource->collection()->toArray();

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
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-05'), $this->created);
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), $this->created);
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-07'), $this->created);
        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);
        $this->dataSource->add($movement3);
        $movements = $this->dataSource->collection()->filterDateLessThan($date)->toArray();

        $this->assertEquals(1, count($movements));
        $this->assertEquals($movement2->id()->serialize(), $movements[0]->id()->serialize());
    }

    /**
     * @test
     */
    public function amountSum()
    {
        $this->dataSource->add(new Movement(new MovementId(), 20, 'pis', new \DateTime('2015-09-05'), $this->created));
        $this->dataSource->add(new Movement(new MovementId(), 30, 'pis', new \DateTime('2015-09-05'), $this->created));

        $this->assertEquals(50, $this->collection->amountSum());
    }

    /**
     * @test
     */
    public function filterByCreatedIsLessThan()
    {
        $date = new \DateTime('2015-09-05 15:20:00');
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), new \DateTime('2015-09-04 00:00:00'));
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), new \DateTime('2015-09-05 15:20:00'));
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), new \DateTime('2015-09-06 00:00:00'));
        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);
        $this->dataSource->add($movement3);
        $movements = $this->dataSource->collection()->filterByCreatedIsLessThan($date)->toArray();

        $this->assertEquals(1, count($movements));
        $this->assertEquals($movement1->id()->serialize(), $movements[0]->id()->serialize());
    }

    /**
     * @test
     */
    public function filterByDateIs()
    {
        $movement1 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-01'), $this->created);
        $movement2 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-02'), $this->created);
        $movement3 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-02'), $this->created);
        $movement4 = new Movement(new MovementId(), 1, 'pis', new \DateTime('2015-09-05'), $this->created);
        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);
        $this->dataSource->add($movement3);
        $this->dataSource->add($movement4);
        $movements = $this->dataSource->collection()->filterByDateIs(new \DateTime('2015-09-02'))->toArray();

        $this->assertEquals(2, count($movements));
    }

    /**
     * @test
     */
    public function filterByOwnedByPeriodicalMovement()
    {
        $periodicalMovement = new PeriodicalMovement(
            new PeriodicalMovementId(),
            1,
            'a',
            new \DateTime(),
            new DaysPeriod(1)
        );
        $movement1 = Movement::fromPeriodicalMovement($periodicalMovement, new \DateTime());
        $movement2 = new Movement(new MovementId(), 2, 'b', new \DateTime(), $this->created);

        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);

        $movements = $this
            ->dataSource
            ->collection()
            ->filterByPeriodicalMovement($periodicalMovement)
            ->toArray();

        $this->assertEquals(1, count($movements));
        $this->assertEquals($periodicalMovement, $movements[0]->periodicalMovement());
    }

    /**
     * @test
     */
    public function first()
    {
        $movement1 = new Movement(new MovementId(), 1, 'a', new \DateTime(), $this->created);
        $movement2 = new Movement(new MovementId(), 2, 'b', new \DateTime(), $this->created);

        $this->dataSource->add($movement1);
        $this->dataSource->add($movement2);

        $movement = $this->dataSource->collection()->first();

        $this->assertEquals($movement1, $movement);
    }
}