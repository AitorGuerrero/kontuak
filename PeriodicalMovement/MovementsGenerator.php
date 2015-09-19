<?php

namespace Kontuak\PeriodicalMovement;

use Kontuak\Movement;
use Kontuak\PeriodicalMovement;

class MovementsGenerator
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var \DateTime */
    private $timeStamp;
    private $idGenerator;
    /** @var Movement\Factory */
    private $movementFactory;

    public function __construct(
        Movement\Source $movementsSource,
        Movement\Factory $movementFactory,
        \DateTime $timeStamp
    ) {
        $this->movementsSource = $movementsSource;
        $this->timeStamp = $timeStamp;
        $this->movementFactory = $movementFactory;
    }

    public function toDate(PeriodicalMovement $periodicalMovement, \DateTime $limitDate)
    {
        $date = $this->firstDate($periodicalMovement);
        $toFormatted = $limitDate->format('Y-m-d');
        $movements = [];
        while($date <= $toFormatted) {
            $movements[] = $this->atDate($periodicalMovement, new \DateTime($date));
            $date = $periodicalMovement->period()->next(new \DateTime($date))->format('Y-m-d');
        }
        return $movements;
    }

    /**
     * @param PeriodicalMovement $periodicalMovement
     * @param \DateTime|\DateTimeInterface $date
     * @return Movement
     */
    public function atDate(PeriodicalMovement $periodicalMovement, \DateTime $date)
    {
        $movement = $this->movementFactory->make(
            $this->movementsSource->newId(),
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