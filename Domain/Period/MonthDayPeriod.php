<?php

namespace Kontuak\Period;

use Kontuak\Period;

class MonthDayPeriod extends Period
{
    function next()
    {
        $targetDay = $this->targetDay();
        $this->advanceMonth();
        $targetMonth = $this->targetMonth();
        $this->setTargetDay($targetDay);
        $this->correctMonthChange($targetMonth);
    }

    private function advanceMonth()
    {
        $this->currentDate->setDate(
            $this->currentDate->format('Y'),
            (int)$this->currentDate->format('m') + 1,
            1
        );
    }

    /**
     * @param $targetDay
     */
    private function setTargetDay($targetDay)
    {
        $this->currentDate->setDate($this->currentDate->format('Y'), $this->currentDate->format('m'), $targetDay);
    }

    /**
     * @param $targetMonth
     */
    private function correctMonthChange($targetMonth)
    {
        if ($this->targetMonth() !== $targetMonth) {
            $this->currentDate->setDate($this->currentDate->format('Y'), $this->currentDate->format('m'), 0);
        }
    }

    /**
     * @return int
     */
    private function targetDay()
    {
        return (int)$this->currentDate->format('d');
    }

    /**
     * @return int
     */
    private function targetMonth()
    {
        return (int)$this->currentDate->format('m');
    }
}