<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Movement;
use Kontuak\PeriodicalMovement;

class MovementsGenerator
{
    /** @var MovementsCollection */
    private $movementsCollection;
    /** @var \DateTimeInterface */
    private $timeStamp;

    public function __construct(MovementsCollection $movementsCollection, \DateTimeInterface $timeStamp)
    {
        $this->movementsCollection = $movementsCollection;
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
        return $this->movementsCollection
            ->filterByPeriodicalMovement($periodicalMovement)
            ->orderByDateDesc()
            ->first();
    }
}