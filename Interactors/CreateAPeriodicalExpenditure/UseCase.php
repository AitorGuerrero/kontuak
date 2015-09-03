<?php

namespace Kontuak\Interactors\CreateAPeriodicalExpenditure;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Period\DaysPeriod;
use Kontuak\Period\MonthDayPeriod;
use Kontuak\PeriodicalMovement;

class UseCase
{
    /**
     * @var PeriodicalMovement\Source
     */
    private $periodicalMovementSource;

    public function __construct(PeriodicalMovement\Source $periodicalMovementSource)
    {
        $this->periodicalMovementSource = $periodicalMovementSource;
    }

    public function execute(Request $request)
    {
        $period = $this->periodFactory($request->periodType, $request->periodAmount);
        $periodicalMovement = new PeriodicalMovement(
            new PeriodicalMovement\Id(),
            -abs($request->amount),
            $request->concept,
            new \DateTime($request->starts),
            $period
        );
        $periodicalMovement->endsAt(new \DateTime($request->ends));
        $this->periodicalMovementSource->add($periodicalMovement);

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
            case Request::TYPE_MONTH_DAY:
                return new MonthDayPeriod($amount);
            default:
                throw new InvalidArgumentException();
        }
    }
}