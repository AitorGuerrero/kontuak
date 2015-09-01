<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Movement;
use Kontuak\MovementsSource;
use Kontuak\PeriodicalMovement;

class MovementsGenerator
{
    /** @var MovementsSource */
    private $movementsSource;
    /** @var \DateTimeInterface */
    private $timeStamp;

    public function __construct(MovementsSource $movementsSource, \DateTimeInterface $timeStamp)
    {
        $this->movementsSource = $movementsSource;
        $this->timeStamp = $timeStamp;
    }

    public function generate(PeriodicalMovement $periodicalMovement)
    {
        $date = $this->firstDate($periodicalMovement);
        $toFormatted = $this->timeStamp->format('Y-m-d');
        $movements = [];
        while($date <= $toFormatted) {
            $movements[] = Movement::fromPeriodicalMovement($periodicalMovement, new \DateTime($date));
            $date = $periodicalMovement->period()->next(new \DateTime($date))->format('Y-m-d');
        }
        return $movements;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @return string
     */
    private function firstDate(PeriodicalMovement $periodicalMovement)
    {
        $lastGeneratedMovement = $this->findLastGeneratedMovement($periodicalMovement);
        if ($lastGeneratedMovement !== null) {
            $date = $periodicalMovement
                ->period()
                ->next($lastGeneratedMovement->date())
                ->format('Y-m-d');
        } else {
            $date = $periodicalMovement->starts()->format('Y-m-d');
        }

        return $date;
    }

    private function findLastGeneratedMovement(PeriodicalMovement $periodicalMovement)
    {
        return $this->movementsSource
            ->collection()
            ->filterByPeriodicalMovement($periodicalMovement)
            ->orderByDateDesc()
            ->first();
    }
}