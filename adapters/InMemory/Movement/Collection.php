<?php

namespace Kontuak\Adapters\InMemory\Movement;

use Kontuak\Movement\Id;
use Kontuak\PeriodicalMovement;
use Kontuak\Movement;

class Collection implements Movement\Collection
{

    use \Kontuak\Adapters\InMemory\Collection;

    const ORDER_DATE = 'date';
    const ORDER_DIRECTION_ASC = 'asc';
    const ORDER_DIRECTION_DESC = 'desc';
    const FILTER_DATE_LESS_THAN = 'dateLessThan';
    const FILTER_ID = 'id';
    const FILTER_CREATED_IS_LESS_THAN = 'createdIsLessThan';
    const FILTER_DATE_IS = 'dateIs';
    const FILTER_PERIODICAL_MOVEMENT = 'periodicalMovement';

    /**
     * @param Movement[] $arrayCollection
     */
    public function __construct($arrayCollection)
    {
        $this->collection = $arrayCollection;
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

    /**
     * @return Collection
     */
    public function orderByDate()
    {
        $ordered = $this->collection;
        usort($ordered, function(Movement $a, Movement $b) {
            return $a->date() > $b->date() ? 1 : -1;
        });

        return new Collection($ordered);
    }

    /**
     * @return Collection
     */
    public function orderByDateDesc()
    {
        $output = $this->orderByDate();
        $output->collection = array_reverse($output->collection);

        return $output;
    }

    /**
     * @param \DateTimeInterface $date
     * @return Collection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        $filter = $date->format('Y-m-d');

        return new Collection(
            array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->date()->format('Y-m-d') < $filter;
            })
        );
    }

    /**
     * @param \DateTimeInterface $date
     * @return Collection
     */
    public function filterDateLessOrEqualTo(\DateTimeInterface $date)
    {
        $filter = $date->format('Y-m-d');

        return new Collection(
            array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->date()->format('Y-m-d') <= $filter;
            })
        );
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return Collection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime)
    {
        $filter = $dateTime->getTimeStamp();

        return new Collection(
            array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->created()->getTimeStamp() < $filter;
            })
        );
    }

    /**
     * @param \DateTimeInterface $date
     * @return Collection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        $filter = $date->format('Y-m-d');

        return new Collection(
            array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->date()->format('Y-m-d') === $filter;
            })
        );
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return Collection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $filter = $periodicalMovement->id()->toString();

        return new Collection(
            array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->periodicalMovement() !== null && $a->periodicalMovement()->id()->toString() === $filter;
            })
        );
    }

    /**
     * @param \DateTime $timeStamp
     * @return Collection
     */
    public function filterByDateIsPostThan(\DateTime $timeStamp)
    {
        $filter = $timeStamp->format('Y-m-d');

        return new Collection(
            array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->date()->format('Y-m-d') > $filter;
            })
        );
    }

    /**
     * @param Id $id
     * @return Collection
     */
    public function byId(Id $id)
    {
        return new Collection(
            array_filter($this->collection, function (Movement $a) use ($id) {
                return $a->id()->toString() === $id->toString();
            })
        );
    }
}