<?php

namespace Kontuak\Interactors\ApplyPeriodicalMovements;

use Kontuak\MovementsCollection;
use Kontuak\PeriodicalMovement\Collection as PeriodicalMovementsCollection;
use Kontuak\PeriodicalMovement\MovementsGenerator;

class UseCase
{
    /** @var MovementsCollection */
    private $movementsCollection;
    /** @var PeriodicalMovementCollection */
    private $periodicalMovementsCollection;
    /** @var \DateTimeInterface */
    private $timeStamp;
    /** @var MovementsGenerator */
    private $movementsGenerator;

    public function __construct(
        MovementsCollection $movementsCollection,
        PeriodicalMovementsCollection $periodicalMovementsCollection,
        \DateTimeInterface $timeStamp,
        MovementsGenerator $movementsGenerator
    ) {
        $this->movementsCollection = $movementsCollection;
        $this->periodicalMovementsCollection = $periodicalMovementsCollection;
        $this->timeStamp = $timeStamp;
        $this->movementsGenerator = $movementsGenerator;
    }

    public function execute()
    {
        $periodicalMovements = $this->periodicalMovementsCollection->all();
        foreach($periodicalMovements as $periodicalMovement) {
            foreach($this->movementsGenerator->generate($periodicalMovement) as $movement) {
                $this->movementsCollection->add($movement);
            }
        }
    }
}