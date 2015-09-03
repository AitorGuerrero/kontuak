<?php

namespace Kontuak\Implementation\InMemory\Movement;

use Kontuak\Movement\Id;
use Kontuak\PeriodicalMovement;
use Kontuak\Movement;

class Collection implements Movement\Collection
{

    const ORDER_DATE = 'date';
    const ORDER_DIRECTION_ASC = 'asc';
    const ORDER_DIRECTION_DESC = 'desc';
    const FILTER_DATE_LESS_THAN = 'dateLessThan';
    const FILTER_ID = 'id';
    const FILTER_CREATED_IS_LESS_THAN = 'createdIsLessThan';
    const FILTER_DATE_IS = 'dateIs';
    const FILTER_PERIODICAL_MOVEMENT = 'periodicalMovement';
    /** @var Movement[] */
    private $collection = [];
    /** @var Source */
    private $source;

    /**
     * @param Source $source
     */
    public function __construct(Source $source)
    {
        $this->collection = $source->toArray();
        $this->source = $source;
    }

    /**
     * float
     */
    public function amountSum()
    {
        $total = 0;
        foreach($this as $movement) {
            $total += $movement->amount();
        }

        return $total;
    }

    public function orderByDate()
    {
        usort($this->collection, function(Movement $a, Movement $b) {
            return $a->date() > $b->date() ? 1 : -1;
        });

        return $this;
    }

    /**
     * @return $this
     */
    public function orderByDateDesc()
    {
        $this->orderByDate();
        $this->collection = array_reverse($this->collection);

        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return Movement\Collection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        $filter = $date->format('Y-m-d');
        $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
            return $a->date()->format('Y-m-d') < $filter;
        });

        return $this;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return Movement\Collection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime)
    {
        $filter = $dateTime->getTimeStamp();
        $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
            return $a->created()->getTimeStamp() < $filter;
        });

        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return Movement\Collection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        $filter = $date->format('Y-m-d');
        $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
            return $a->date()->format('Y-m-d') === $filter;
        });

        return $this;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return Movement\Collection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $filter = $periodicalMovement->id()->serialize();
        $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
            return $a->periodicalMovement() !== null && $a->periodicalMovement()->id()->serialize() === $filter;
        });

        return $this;
    }

    public function current()
    {
        return current($this->collection);
    }

    public function next()
    {
        return next($this->collection);
    }

    public function key()
    {
        return key($this->collection);
    }

    public function valid()
    {
        return isset($this->collection[key($this->collection)]);
    }

    public function rewind()
    {
        reset($this->collection);
    }

    public function count()
    {
        return count($this->collection);
    }

    /**
     * @param Id $id
     * @return \Kontuak\Movement
     */
    public function findById(Id $id)
    {
        return $this->source->toArray()[$id->serialize()];
    }
}