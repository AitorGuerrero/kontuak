<?php

namespace kontuak\Interactors\CreateAPeriodicalEntry;

class UseCase
{
    public function execute(Request $request)
    {
        $period = $this->periodFactory($request->periodType, $request->periodAmount);
        $periodicalMovement = new PeriodicalExpenditure($request->amount, $request->concept, $period);
        $this->periodicalMovementCollection->add($periodicalMovement);
    }

    /**
     * @param $type
     * @param $amount
     * @throws PeriodTypeDoNotExistsException
     * @return \Kontuak\Period
     */
    protected function periodFactory($type, $amount)
    {
        switch ($type) {
            case Request::TYPE_DAYS:
                return new DaysPeriod($amount);
            case Request::TYPE_MONTHS:
                return new MonthPeriod($amount);
            case Request::TYPE_WEEK_DAY:
                return new WeekDayPeriod($amount);
            case Request::TYPE_MONTH_DAY:
                return new MonthDayPeriod($amount);
            default:
                throw new PeriodTypeDoNotExistsException($type);
        }
    }
}