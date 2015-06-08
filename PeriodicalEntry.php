<?php

namespace Kontuak;

use DateTime;

class PeriodicalEntry extends PeriodicalMovement
{
    /**
     * @return Entry
     */
    protected function generateTemplateMovement()
    {
        return new Entry($this->amount, $this->concept, new DateTime());
    }
}