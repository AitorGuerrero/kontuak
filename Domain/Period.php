<?php

namespace Kontuak;

abstract class Period
{
    /** @var int */
    private $amount;
    /** @var IsoDateTime */
    private $startDate;
    /** @var IsoDateTime */
    private $endDate;

    public function __construct($amount, IsoDateTime $startDate = null, IsoDateTime $endDate = null)
    {
        $this->amount = $amount;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return IsoDateTime
     */
    public function startDate()
    {
        return clone($this->startDate);
    }

    /**
     * @return IsoDateTime
     */
    public function endDate()
    {
        return clone($this->endDate);
    }
}