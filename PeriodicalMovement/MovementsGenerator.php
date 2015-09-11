<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\Implementation\InMemory\MovementsCollection;
use Kontuak\Movement;
use Kontuak\MovementsSource;
use Kontuak\PeriodicalMovement;

class MovementsGenerator
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var \DateTimeInterface */
    private $timeStamp;

    public function __construct(Movement\Source $movementsSource, \DateTimeInterface $timeStamp)
    {
        $this->movementsSource = $movementsSource;
        $this->timeStamp = $timeStamp;
    }

    public function all(PeriodicalMovement $periodicalMovement)
    {
        $date = $this->firstDate($periodicalMovement);
        $toFormatted = $this->timeStamp->format('Y-m-d');
        $movements = [];
        while($date <= $toFormatted) {
            $movements[] = $this->atDate($periodicalMovement, new \DateTime($date));
            $date = $periodicalMovement->period()->next(new \DateTime($date))->format('Y-m-d');
        }
        return $movements;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @param \DateTimeInterface $date
     * @return Movement
     */
    public static function atDate(PeriodicalMovement $periodicalMovement, \DateTimeInterface $date)
    {
        $generator = new Movement\Id\Generator(); // TODO Injection
        $movement = new Movement(
            $generator->generate(),
            $periodicalMovement->amount(),
            $periodicalMovement->concept(),
            $date,
            new \DateTime() // TODO Injection
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
            ->current();
    }
}