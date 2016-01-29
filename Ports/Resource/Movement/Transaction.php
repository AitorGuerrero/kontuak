<?php

namespace Kontuak\Ports\Resource\Movement;

use Kontuak\Movement\Transaction as DomainTransaction;
use Kontuak\Ports\Resource\Movement;

class Transaction
{
    /** @var Movement */
    private $movement;
    /** @var float */
    private $amount;


    public function __construct(DomainTransaction $domainTransaction)
    {
        $this->movement = new Movement($domainTransaction->movement());
        $this->amount = $domainTransaction->amount();
    }

    /**
     * @return Movement
     */
    public function movement()
    {
        return $this->movement;
    }

    /**
     * @return float
     */
    public function amount()
    {
        return $this->amount;
    }
}
