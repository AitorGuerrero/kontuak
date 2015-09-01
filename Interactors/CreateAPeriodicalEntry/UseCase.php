<?php

namespace Kontuak\Interactors\CreateAPeriodicalEntry;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Period\DaysPeriod;
use Kontuak\Period\MonthDayPeriod;
use Kontuak\Period\MonthsPeriod;
use Kontuak\Period\WeekDayPeriod;
use Kontuak\PeriodicalExpenditure;
use Kontuak\PeriodicalMovement;
use Kontuak\PeriodicalMovementId;

class UseCase
{
    private $periodicalMovementCollection;

    public function __construct($periodicalMovementCollection)
    {
        $this->periodicalMovementCollection = $periodicalMovementCollection;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function execute(Request $request)
    {
        $periodicalMovement = new PeriodicalMovement(
            new PeriodicalMovementId(),
            abs($request->amount),
            $request->concept,
            new \DateTime($request->starts),
            $this->periodFactory($request->periodType, $request->periodAmount)
        );
        $this->periodicalMovementCollection->add($periodicalMovement);
        $response = new Response();
        $response->periodicalMovement = [
            'id' => $periodicalMovement->id()->serialize()
        ];

        return $response;
    }

    /**
     * @param $type
     * @param $amount
     * @throws InvalidArgumentException
     * @return \Kontuak\Period
     */
    protected function periodFactory($type, $amount)
    {
        switch ($type) {
            case Request::TYPE_DAYS:
                return new DaysPeriod($amount);
            case Request::TYPE_MONTHS:
                return new MonthsPeriod($amount);
            case Request::TYPE_WEEK_DAY:
                return new WeekDayPeriod($amount);
            case Request::TYPE_MONTH_DAY:
                return new MonthDayPeriod($amount);
            default:
                throw new InvalidArgumentException($type);
        }
    }
}