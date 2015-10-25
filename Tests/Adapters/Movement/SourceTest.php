<?php

namespace Kontuak\Tests\Adapters\Movement;

use Kontuak\Adapters\InMemory\Movement\Factory;
use Kontuak\Adapters\InMemory\PeriodicalMovement\Factory as PeriodicalMovementFactory;
use Kontuak\Movement;
use Kontuak\Period\DaysPeriod;
use Kontuak\PeriodicalMovement;
use Kontuak\Adapters\InMemory\Movement\Source;

trait SourceTest
{
    /** @var PeriodicalMovementFactory */
    private $periodicalMovementFactory;
    /** @var Source */
    protected $source;
    /** @var \DateTime */
    protected $timeStamp;

    /**
     * @test
     */
    public function shouldPersistAMovement()
    {
        $movement = $this->movementGenerator();
        $this->source->add($movement);
        $retrievedMovement = $this->source->collection()->current();

        $this->assertSame($movement, $retrievedMovement);
    }

    /**
     * @test
     */
    public function shouldFilterDateLessThan()
    {
        $movement1 = $this->movementGenerator(null, null, new \DateTime('2015-05-04'));
        $movement2 = $this->movementGenerator(null, null, new \DateTime('2015-06-04'));
        $this->source->add($movement1);
        $this->source->add($movement2);
        $collection = $this->source->collection()->filterDateLessThan(new \DateTime('2015-06-01'));

        $this->assertEquals(1, $collection->count());
        $this->assertSame($collection->current(), $movement1);
    }

    /**
     * @test
     */
    public function shouldFilterByCreatedIsLessThan()
    {
        $movement1 = $this->movementGenerator(null, null, null, new \DateTime('2015-05-10'));
        $movement2 = $this->movementGenerator(null, null, null, new \DateTime('2015-05-01'));

        $this->source->add($movement1);
        $this->source->add($movement2);
        $collection = $this->source->collection()->filterByCreatedIsLessThan(new \DateTime('2015-05-05'));

        $this->assertEquals(1, $collection->count());
        $this->assertSame($collection->current(), $movement2);
    }

    /**
     * @test
     */
    public function shouldFilterByDateIs()
    {
        $movement1 = $this->movementGenerator(null, null, new \DateTime('2015-05-04'));
        $movement2 = $this->movementGenerator(null, null, new \DateTime('2015-06-04'));
        $movement3 = $this->movementGenerator(null, null, new \DateTime('2015-06-05'));

        $this->source->add($movement1);
        $this->source->add($movement2);
        $this->source->add($movement3);
        $collection = $this->source->collection()->filterByDateIs(new \DateTime('2015-06-04'));

        $this->assertEquals(1, $collection->count());
        $this->assertSame($collection->current(), $movement2);
    }

    /**
     * @test
     */
    public function shouldFilterByPeriodicalMovement()
    {
        $movementsGenerator = new PeriodicalMovement\MovementsGenerator(
            $this->source,
            new Factory(),
            $this->timeStamp
        );
        $periodicalMovement = $this->periodicalMovementFactory->make(
            new PeriodicalMovement\Id(uniqid()),
            100,
            'pus',
            new \DateTime('2015-05-01'),
            new DaysPeriod(4)
        );
        $movement1 = $this->movementGenerator();
        $movement2 = $movementsGenerator->atDate($periodicalMovement, new \DateTime('2015-05-01'));

        $this->source->add($movement1);
        $this->source->add($movement2);
        $collection = $this->source->collection()->filterByPeriodicalMovement($periodicalMovement);

        $this->assertEquals(1, $collection->count());
        $this->assertSame($collection->current(), $movement2);
    }

    /**
     * @test
     */
    public function shouldOrderByDate()
    {
        $movement1 = $this->movementGenerator(null, null, new \DateTime('2015-05-10'));
        $movement2 = $this->movementGenerator(null, null, new \DateTime('2015-05-01'));
        $movement3 = $this->movementGenerator(null, null, new \DateTime('2015-05-05'));

        $this->source->add($movement1);
        $this->source->add($movement2);
        $this->source->add($movement3);
        $collection = $this->source->collection()->orderByDate();

        $this->assertSame($collection->current(), $movement2);
        $this->assertSame($collection->next(), $movement3);
        $this->assertSame($collection->next(), $movement1);
    }

    /**
     * @test
     */
    public function shouldOrderByDateDesc()
    {
        $movement1 = $this->movementGenerator(null, null, new \DateTime('2015-05-10'));
        $movement2 = $this->movementGenerator(null, null, new \DateTime('2015-05-01'));
        $movement3 = $this->movementGenerator(null, null, new \DateTime('2015-05-05'));

        $this->source->add($movement1);
        $this->source->add($movement2);
        $this->source->add($movement3);
        $collection = $this->source->collection()->orderByDateDesc();

        $this->assertSame($collection->current(), $movement1);
        $this->assertSame($collection->next(), $movement3);
        $this->assertSame($collection->next(), $movement2);
    }

    /**
     * @test
     */
    public function shouldSumAmounts()
    {
        $this->source->add($this->movementGenerator(9));
        $this->source->add($this->movementGenerator(1.5));
        $this->source->add($this->movementGenerator(-5));

        $this->assertSame($this->source->collection()->amountSum(), 5.5);
    }

    public function movementGenerator($amount = null, $concept = null, $date = null, $created = null)
    {
        $factory = new Factory();
        return $factory->make(
            new Movement\Id(uniqid()),
            $amount === null ? 300 : $amount,
            $concept === null ? 'Concept' : $concept,
            $date === null ? new \DateTime('2015-05-01') : $date,
            $created === null ? new \DateTime('2015-05-01') : $created
        );
    }
}