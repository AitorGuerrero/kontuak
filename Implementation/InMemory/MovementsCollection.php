<?php
/**
 * Created by PhpStorm.
 * User: aitor.guerrero
 * Date: 24/8/15
 * Time: 9:40
 */

namespace Kontuak\Implementation\InMemory;

use Kontuak\Movement;
use Kontuak\PeriodicalMovement;

class MovementsCollection implements \Kontuak\MovementsCollection
{
    const ORDER_DATE = 'orderDate';
    const ORDER_DATE_DESC = 'orderDateDesc';

    protected $collection = [];
    protected $identifierCounter = 1;
    /** @var \DateTimeInterface */
    protected $timeStamp;
    private $limit;
    private $order;
    private $filters = [];

    public function __construct(\DateTimeInterface $timeStamp)
    {
        $this->timeStamp = $timeStamp;
    }

    public function add(Movement $movement)
    {
        $movement->identify(new EntityId($this->identifierCounter++));
        $movement->setCreated($this->timeStamp);
        $this->collection[$movement->id()->serialize()] = $movement;
    }

    /**
     * @param \Kontuak\EntityId $id
     * @return Movement
     */
    public function find(\Kontuak\EntityId $id)
    {
        return $this->collection[$id->serialize()];
    }

    public function orderByDate()
    {
        $this->order = self::ORDER_DATE;
        return $this;
    }

    public function orderByDateDesc()
    {
        $this->order = self::ORDER_DATE_DESC;
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

    /**
     * @return Movement[]
     */
    public function all()
    {
        return $this->processCollection();
    }

    private function sortByDate($collection)
    {
        usort($collection, function($a, $b) {
            return $a->date()->format('Y-m-d') < $b->date()->format('Y-m-d') ? -1 : 1;
        });

        return $collection;
    }

    private function sortByDateDesc($collection)
    {
        return array_reverse($this->sortByDate($collection));
    }

    /**
     * @param \DateTimeInterface $date
     * @return MovementsCollection
     */
    public function filterDateLessThan(\DateTimeInterface $date)
    {
        $this->filters['dateLessThan'] = $date;
        return $this;
    }

    private function applyFilterDateLessThan($collection, \DateTimeInterface $dateLessThan)
    {
        $serializedDate = $dateLessThan->format('Y-m-d');
        return array_filter($collection, function (Movement $movement) use ($serializedDate) {
            return $movement->date()->format('Y-m-d') < $serializedDate;
        });
    }

    /**
     * float
     */
    public function amountSum()
    {
        $collection = $this->processCollection();
        $amount = 0;
        foreach($collection as $movement) {
            $amount += $movement->amount();
        }
        return $amount;
    }

    private function applyFilters($collection)
    {
        if (isset($this->filters['dateLessThan'])) {
            $collection = $this->applyFilterDateLessThan($collection, $this->filters['dateLessThan']);
        }
        if (isset($this->filters['createdIsLessThan'])) {
            $collection = $this->applyFilterByCreatedIsLessThan($collection, $this->filters['createdIsLessThan']);
        }
        if (isset($this->filters['dateIs'])) {
            $collection = $this->applyFilterByDateIs($collection, $this->filters['dateIs']);
        }
        if(isset($this->filters['PeriodicalMovement'])) {
            $collection = $this->applyFilterByPeriodicalMovement($collection);
        }

        return $collection;
    }


    /**
     * @param \DateTimeInterface $dateTime
     * @return MovementsCollection
     */
    public function filterByCreatedIsLessThan(\DateTimeInterface $dateTime)
    {
        $this->filters['createdIsLessThan'] = $dateTime;
        return $this;
    }

    private function applyFilterByCreatedIsLessThan($collection, \DateTimeInterface $dateTime)
    {
        return array_filter($collection, function (Movement $movement) use ($dateTime) {
            return $movement->created() < $dateTime;
        });
    }

    /**
     * @param \DateTimeInterface $date
     * @return MovementsCollection
     */
    public function filterByDateIs(\DateTimeInterface $date)
    {
        $this->filters['dateIs'] = $date;
        return $this;
    }

    private function applyFilterByDateIs($collection, \DateTimeInterface $dateTime)
    {
        $dateTimeFormatted = $dateTime->format('Y-m-d');
        return array_filter($collection, function (Movement $movement) use ($dateTimeFormatted) {
            return $movement->date()->format('Y-m-d') === $dateTimeFormatted;
        });
    }

    /**
     * @param Movement[] $collection
     * @return \Kontuak\Movement[]
     */
    private function applyOrder($collection)
    {
        if($this->order === self::ORDER_DATE_DESC) {
            return $this->sortByDateDesc($collection);
        } else if ($this->order === self::ORDER_DATE) {
            return $this->sortByDate($collection);
        }
        return $collection;
    }

    public function filterByPeriodicalMovement(PeriodicalMovement $periodicalMovement)
    {
        $this->filters['PeriodicalMovement'] = $periodicalMovement;
        return $this;
    }

    private function applyFilterByPeriodicalMovement($collection)
    {
        $periodicalMovement = $this->filters['PeriodicalMovement'];
        return array_filter($collection, function(Movement $movement) use ($periodicalMovement) {
            return $movement->periodicalMovement() !== null &&
            $movement->periodicalMovement() === $periodicalMovement;
        });
    }

    public function first()
    {
        $collection = $this->processCollection();

        return !empty($collection) ? $collection[0] : null;
    }

    /**
     * @return array|\Kontuak\Movement[]
     */
    private function processCollection()
    {
        $collection = $this->applyFilters($this->collection);
        $collection = $this->applyOrder($collection);
        if ($this->limit !== null) {
            $collection = array_slice($collection, 0, $this->limit);
        }
        $collection = array_values($collection);
        return $collection;
    }
}