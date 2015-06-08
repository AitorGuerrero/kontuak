<?php

namespace Kontuak;

class Entry extends Movement
{
    /**
     * @param mixed $amount
     */
    protected function updateAmount($amount)
    {
        $this->amount = abs($amount);
    }
}