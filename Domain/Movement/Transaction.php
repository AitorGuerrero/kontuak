<?php

namespace Kontuak\Movement;

use Kontuak\Movement;

class Transaction
{
    /** @var Movement */
    private $movement;
    /** @var float */
    private $amount;

    public function __construct(Movement $movement, $amount)
    {
        $this->movement = $movement;
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return Movement
     */
    public function movement()
    {
        return $this->movement;
    }
}
