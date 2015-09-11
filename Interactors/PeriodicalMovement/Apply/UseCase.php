<?php

namespace Kontuak\Interactors\PeriodicalMovement\Apply;

use Kontuak\Movement;
use Kontuak\PeriodicalMovement;

class UseCase
{
    /** @var Movement\Source */
    private $movementsSource;
    /** @var PeriodicalMovement\Source */
    private $periodicalMovementsSource;
    /** @var \DateTimeInterface */
    private $timeStamp;
    /** @var PeriodicalMovement\MovementsGenerator */
    private $movementsGenerator;

    public function __construct(
        Movement\Source $movementsSource,
        PeriodicalMovement\Source $periodicalMovementsSource,
        \DateTimeInterface $timeStamp,
        PeriodicalMovement\MovementsGenerator $movementsGenerator
    ) {
        $this->movementsSource = $movementsSource;
        $this->periodicalMovementsSource = $periodicalMovementsSource;
        $this->timeStamp = $timeStamp;
        $this->movementsGenerator = $movementsGenerator;
    }

    public function execute()
    {
        $periodicalMovements = $this->periodicalMovementsSource->collection()->all();
        foreach($periodicalMovements as $periodicalMovement) {
            foreach($this->movementsGenerator->all($periodicalMovement) as $movement) {
                $this->movementsSource->add($movement);
            }
        }
    }
}