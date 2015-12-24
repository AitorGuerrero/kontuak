<?php

namespace Kontuak\PeriodicalMovement\Event;

class PeriodUpdated extends AttributeUpdated
{
    /**
     * @return string
     */
    protected function attributeName()
    {
        return 'period';
    }
}
