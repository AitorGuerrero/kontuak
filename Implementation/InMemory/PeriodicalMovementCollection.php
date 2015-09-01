<?php

namespace Kontuak\Implementation\InMemory;

use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovement\Collection;
use Kontuak\PeriodicalMovementId;

class PeriodicalMovementCollection implements Collection
{
    const ORDER_DATE = 'date';
    const ORDER_DIRECTION_ASC = 'asc';
    const ORDER_DIRECTION_DESC = 'desc';
    /** @var PeriodicalMovementsSource */
    private $source;
    private $order;
    private $orderDirection;
    /** @var PeriodicalMovement[] */
    private $collection = [];

    public function __construct(PeriodicalMovementsSource $source)
    {
        $this->source = $source;
    }

    /**
     * @param PeriodicalMovementId $id
     * @return PeriodicalMovement
     */
    public function find(PeriodicalMovementId $id)
    {
        return $this->source->toArray()[$id->serialize()];
    }

    public function all()
    {
        return $this->processCollection();
    }

    public function orderByDate()
    {
        $this->order = self::ORDER_DATE;
        $this->orderDirection = self::ORDER_DIRECTION_ASC;
        return $this;
    }

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

    private function applyOrderDate()
    {
        usort($this->collection, function(PeriodicalMovement $a, PeriodicalMovement $b) {
            return $a->date() > $b->date() ? 1 : -1;
        });
    }

    private function applyLimit()
    {
    }
}