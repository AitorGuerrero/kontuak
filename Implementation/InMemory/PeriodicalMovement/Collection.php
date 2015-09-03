<?php

namespace Kontuak\Implementation\InMemory\PeriodicalMovement;

use Kontuak\PeriodicalMovement;

class Collection implements PeriodicalMovement\Collection
{
    const ORDER_DATE = 'date';
    const ORDER_DIRECTION_ASC = 'asc';
    const ORDER_DIRECTION_DESC = 'desc';
    /** @var PeriodicalMovement\Source */
    private $source;
    private $orderDirection;
    /** @var PeriodicalMovement[] */
    private $collection = [];

    public function __construct(PeriodicalMovement\Source $source)
    {
        $this->source = $source;
    }

    /**
     * @param PeriodicalMovement\Id $id
     * @return PeriodicalMovement
     */
    public function find(PeriodicalMovement\Id $id)
    {
        return $this->source->toArray()[$id->serialize()];
    }

    public function all()
    {
        return $this->processCollection();
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
        if($this->orderDirection === self::ORDER_DIRECTION_DESC) {
            $this->collection = array_reverse($this->collection);
        }
    }

    private function applyLimit()
    {
    }
}