<?php

namespace Kontuak\Period;

use Kontuak\IsoDateTime;
use Kontuak\Period;

class DaysPeriod extends Period
{
    /** @var int */
    private $amount;

    /**
     * DaysPeriod constructor.
     * @param int $amount
     * @param IsoDateTime $startDate
     * @param IsoDateTime|null $endDate
     */
    public function __construct($amount, IsoDateTime $startDate, IsoDateTime $endDate = null)
    {
        parent::__construct($startDate, $endDate);
        $this->amount = $amount;
    }

    function next()
    {
        $this->current()->add(new \DateInterval('P'.$this->amount.'D'));
    }
}