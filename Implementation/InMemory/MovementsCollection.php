<?php
/**
 * Created by PhpStorm.
 * User: aitor.guerrero
 * Date: 24/8/15
 * Time: 9:40
 */

namespace Kontuak\Implementation\InMemory;

use Kontuak\Movement;
use Kontuak\MovementId;
use Kontuak\MovementsCollection as BaseMovementsCollection;
use Kontuak\PeriodicalMovement;

class MovementsCollection implements BaseMovementsCollection
{

    const ORDER_DATE = 'date';
    const ORDER_DIRECTION_ASC = 'asc';
    const ORDER_DIRECTION_DESC = 'desc';
    const FILTER_DATE_LESS_THAN = 'dateLessThan';
    const FILTER_ID = 'id';
    const FILTER_CREATED_IS_LESS_THAN = 'createdIsLessThan';
    const FILTER_DATE_IS = 'dateIs';
    const FILTER_PERIODICAL_MOVEMENT = 'periodicalMovement';
    /** @var MovementsSource */
    private $source;
    private $order;
    private $orderDirection;
    private $limit;
    /** @var Movement[] */
    private $collection;
    private $filter = [];

    /**
     * @param MovementsSource $source
     */
    public function __construct(MovementsSource $source)
    {
        $this->source = $source;
    }

    /**
     * @return Movement
     */
    public function first()
    {
        $this->processCollection();

        return isset($this->collection[0]) ? $this->collection[0] : null;
    }

    /**
     * @return Movement[]
     */
    public function toArray()
    {
        $result = $this->processCollection();

        return $result;
    }

    /**
     * float
     */
    public function amountSum()
    {
        $result = $this->processCollection();
        $totalAmount = 0;
        foreach($result as $movement) {
            $totalAmount += $movement->amount();
        }

        return $totalAmount;
    }

    public function orderByDate()
    {
        $this->order = self::ORDER_DATE;
        $this->orderDirection = self::ORDER_DIRECTION_ASC;
        return $this;
    }

    /**
     * @return $this
     */
    public function orderByDateDesc()
    {
        $this->order = self::ORDER_DATE;
        $this->orderDirection = self::ORDER_DIRECTION_DESC;
        return $this;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function limit($amount)
    {
        $this->limit = $amount;
        return $this;
    }

    public function filterById(MovementId $id)
    {
        $this->filter[self::FILTER_ID] = $id;
        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return BaseMovementsCollection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        $this->filter[self::FILTER_DATE_LESS_THAN] = $date;
        return $this;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return BaseMovementsCollection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime)
    {
        $this->filter[self::FILTER_CREATED_IS_LESS_THAN] = $dateTime;
        return $this;
    }

    /**
     * @param \DateTimeInterface $date
     * @return BaseMovementsCollection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        $this->filter[self::FILTER_DATE_IS] = $date;
        return $this;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return BaseMovementsCollection
     */
    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $this->filter[self::FILTER_PERIODICAL_MOVEMENT] = $periodicalMovement;
        return $this;
    }

    /**
     * @return Movement[]
     */
    private function processCollection()
    {
        $this->collection = $this->source->toArray();
        $this->applyFilters();
        $this->applyOrder();
        $this->applyLimit();

        return $this->collection = array_values($this->collection);
    }

    private function applyFilters()
    {
        $this->applyFilterId();
        $this->applyDateLessThan();
        $this->applyCreatedLessThan();
        $this->applyFilterDateIs();
        $this->applyFilterPeriodicalMovement();
    }

    private function applyOrder()
    {
        if ($this->order === self::ORDER_DATE) {
            $this->applyOrderDate();
        }
        if($this->orderDirection === self::ORDER_DIRECTION_DESC) {
            $this->collection = array_reverse($this->collection);
        }
    }

    /**
     * @return Movement[]
     */
    private function applyFilterId()
    {
        if(isset($this->filter[self::FILTER_ID])) {
            $id = $this->filter[self::FILTER_ID]->serialize();
            $this->collection = array_filter($this->collection, function(Movement $a) use ($id) {
                return $a->id()->serialize() === $id;
            });
        }
    }

    /**
     * @return Movement[]
     */
    private function applyDateLessThan()
    {
        if(isset($this->filter[self::FILTER_DATE_LESS_THAN])) {
            $filter = $this->filter[self::FILTER_DATE_LESS_THAN]->format('Y-m-d');
            $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->date()->format('Y-m-d') < $filter;
            });
        }
    }

    /**
     * @return Movement[]
     */
    private function applyCreatedLessThan()
    {
        if(isset($this->filter[self::FILTER_CREATED_IS_LESS_THAN])) {
            $filter = $this->filter[self::FILTER_CREATED_IS_LESS_THAN]->getTimeStamp();
            $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->created()->getTimeStamp() < $filter;
            });
        }
    }

    /**
     * @return Movement[]
     */
    private function applyFilterDateIs()
    {
        if(isset($this->filter[self::FILTER_DATE_IS])) {
            $filter = $this->filter[self::FILTER_DATE_IS]->format('Y-m-d');
            $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->date()->format('Y-m-d') === $filter;
            });
        }
    }

    /**
     * @return Movement[]
     */
    private function applyFilterPeriodicalMovement()
    {
        if(isset($this->filter[self::FILTER_PERIODICAL_MOVEMENT])) {
            $filter = $this->filter[self::FILTER_PERIODICAL_MOVEMENT]->id()->serialize();
            $this->collection = array_filter($this->collection, function (Movement $a) use ($filter) {
                return $a->periodicalMovement() !== null && $a->periodicalMovement()->id()->serialize() === $filter;
            });
        }
    }

    private function applyOrderDate()
    {
        usort($this->collection, function(Movement $a, Movement $b) {
            return $a->date() > $b->date() ? 1 : -1;
        });
    }

    private function applyLimit()
    {
        $this->collection = array_slice($this->collection, 0, $this->limit);
    }
}