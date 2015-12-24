<?php

namespace Kontuak\PeriodicalMovement\Event;

class AmountUpdated extends AttributeUpdated
{
    /**
     * @return string
     */
    protected function attributeName()
    {
        return 'amount';
    }
}
