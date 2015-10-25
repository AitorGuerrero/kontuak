<?php

namespace Kontuak\Ports\PeriodicalMovement\Apply;

use Kontuak\Movement;
use Kontuak\PeriodicalMovement;

class UseCase
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var PeriodicalMovement\Source */
    private $periodicalMovementsSource;
    /** @var \DateTime */
    private $timeStamp;
    /** @var PeriodicalMovement\MovementsGenerator */
    private $movementsGenerator;

    public function __construct(
        Movement\Source $movementsSource,
        PeriodicalMovement\Source $periodicalMovementsSource,
        \DateTime $timeStamp,
        PeriodicalMovement\MovementsGenerator $movementsGenerator
    ) {
        $this->movementsSource = $movementsSource;
        $this->periodicalMovementsSource = $periodicalMovementsSource;
        $this->timeStamp = $timeStamp;
        $this->movementsGenerator = $movementsGenerator;
    }

    public function execute()
    {
        $limitDate = clone($this->timeStamp);
        $limitDate->add(new \DateInterval('P3M'));
        $periodicalMovements = $this->periodicalMovementsSource->collection();
        foreach($periodicalMovements as $periodicalMovement) {
            foreach($this->movementsGenerator->toDate($periodicalMovement, $limitDate) as $movement) {
                $this->movementsSource->add($movement);
            }
        }
    }
}