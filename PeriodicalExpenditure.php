<?php

namespace Kontuak;

use DateTime;

class PeriodicalExpenditure extends PeriodicalMovement
{

    /**
     * @return Expenditure
     */
    protected function generateTemplateMovement()
    {
        return new Expenditure($this->amount, $this->concept, new DateTime());
    }
}