<?php

namespace Kontuak;

abstract class Period
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

    abstract function next(\DateTimeInterface $date);
}