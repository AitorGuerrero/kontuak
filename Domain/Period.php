<?php

namespace Kontuak;

use Iterator;

abstract class Period implements Iterator
{
    /** @var IsoDateTime */
    private $startDate;
    /** @var IsoDateTime */
    private $endDate;
    /** @var IsoDateTime */
    protected $currentDate;

    /**
     * Period constructor.
     * @param IsoDateTime $startDate
     * @param IsoDateTime|null $endDate
     */
    public function __construct(IsoDateTime $startDate, IsoDateTime $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->currentDate = clone($this->startDate);
    }

    /**
     * @return IsoDateTime
     */
    public function startDate()
    {
        return clone($this->startDate);
    }

    /**
     * @param IsoDateTime $starts
     */
    public function updateStartDate(IsoDateTime $starts)
    {
        $this->startDate = $starts;
    }

    /**
     * @param IsoDateTime $endDate
     */
    public function endsAt(IsoDateTime $endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->currentDate = new IsoDateTime($this->startDate()->isoDate());
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return IsoDateTime
     * @since 5.0.0
     */
    public function current()
    {
        return clone($this->currentDate);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->currentDate->isoDate();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->currentDate >= $this->startDate
            && (
                is_null($this->endDate)
                || $this->currentDate <= $this->endDate
        );
    }
}
