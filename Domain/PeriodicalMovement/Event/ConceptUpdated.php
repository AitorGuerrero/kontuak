<?php

namespace Kontuak\PeriodicalMovement\Event;

class ConceptUpdated extends AttributeUpdated
{
    /**
     * @return string
     */
    protected function attributeName()
    {
        return 'concept';
    }
}
