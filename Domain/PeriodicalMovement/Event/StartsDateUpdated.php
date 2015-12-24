<?php

namespace Kontuak\PeriodicalMovement\Event;

class StartsDateUpdated extends AttributeUpdated
{
    /**
     * @return string
     */
    protected function attributeName()
    {
        return 'starts date';
    }
}
