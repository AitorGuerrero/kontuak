<?php

namespace Kontuak;

abstract class Period implements \Iterator
{
    private $amount;

    public function __construct($amount)
    {
        $this->amount = $amount;
    }

    public function amount()
    {
        return $this->amount;
    }
}