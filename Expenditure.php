<?php

namespace Kontuak;

class Expenditure extends Movement
{
    /**
     * @param mixed $amount
     */
    protected function updateAmount($amount)
    {
        $this->amount = -abs($amount);
    }
}