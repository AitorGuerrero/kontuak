<?php

namespace Kontuak\Interactors\CreateAPeriodicalMovement;

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

    /**
     * @param Request $request
     * @return Response
     * @throws InvalidArgumentException
     */
    public function execute(Request $request)
    {
        $periodicalMovement = new PeriodicalMovement(
            new PeriodicalMovement\Id(),
            abs($request->amount),
            $request->concept,
            new \DateTime($request->starts),
            $this->periodFactory($request->periodType, $request->periodAmount)
        );
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
                throw new InvalidArgumentException($type);
        }
    }
}