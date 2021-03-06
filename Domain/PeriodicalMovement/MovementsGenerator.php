<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\IsoDateTime;
use Kontuak\Movement;
use Kontuak\PeriodicalMovement;

class MovementsGenerator
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var IsoDateTime */
    private $timeStamp;

    public function __construct(
        Movement\Source $movementsSource,
        IsoDateTime $timeStamp
    ) {
        $this->movementsSource = $movementsSource;
        $this->timeStamp = $timeStamp;
    }

    public function toDate(PeriodicalMovement $periodicalMovement, IsoDateTime $limitDate)
    {
        $date = $this->firstDate($periodicalMovement);
        $movements = [];
        while($date <= $limitDate->format('Y-m-d')) {
            $movements[] = $this->atDate($periodicalMovement, new IsoDateTime($date));
            $date = $periodicalMovement->period()->next(new IsoDateTime($date))->format('Y-m-d');
        }

        return $movements;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @param IsoDateTime $date
     * @return Movement
     */
    public function atDate(PeriodicalMovement $periodicalMovement, IsoDateTime $date)
    {
        $movement = new Movement(
            Movement\Id::make(),
            $periodicalMovement->amount(),
            $periodicalMovement->concept(),
            $date,
            $this->timeStamp
        );
        $movement->assignToPeriodicalMovement($periodicalMovement); // TODO should be private?

        return $movement;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return string
     */
    private function firstDate(PeriodicalMovement $periodicalMovement)
    {
        $lastGeneratedMovement = $this->findLastGeneratedMovement($periodicalMovement);
        if ($lastGeneratedMovement !== false) {
            $date = $periodicalMovement
                ->period()
                ->next($lastGeneratedMovement->date())
                ->format('Y-m-d');
        } else {
            $date = $periodicalMovement->starts()->isoDate();
        }

        return $date;
    }

    private function findLastGeneratedMovement(PeriodicalMovement $periodicalMovement)
    {
        return $this->movementsSource
            ->collection()
            ->filterByPeriodicalMovement($periodicalMovement)
            ->orderByDateDesc()
            ->current();
    }
}