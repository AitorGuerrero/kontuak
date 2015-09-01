<?php

namespace Kontuak\Interactors\ApplyPeriodicalMovements;

use Kontuak\MovementsCollection;
use Kontuak\MovementsSource;
use Kontuak\PeriodicalMovement\MovementsGenerator;
use Kontuak\PeriodicalMovementsSource;

class UseCase
{
    /** @var MovementsSource */
    private $movementsSource;
    /** @var PeriodicalMovementsSource */
    private $periodicalMovementsSource;
    /** @var \DateTimeInterface */
    private $timeStamp;
    /** @var MovementsGenerator */
    private $movementsGenerator;

    public function __construct(
        MovementsSource $movementsSource,
        PeriodicalMovementsSource $periodicalMovementsSource,
        \DateTimeInterface $timeStamp,
        MovementsGenerator $movementsGenerator
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
            foreach($this->movementsGenerator->generate($periodicalMovement) as $movement) {
                $this->movementsSource->add($movement);
            }
        }
    }
}