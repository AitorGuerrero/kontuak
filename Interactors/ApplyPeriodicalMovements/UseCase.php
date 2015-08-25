<?php

namespace Kontuak\Interactors\ApplyPeriodicalMovements;

use Kontuak\MovementsCollection;
use Kontuak\PeriodicalMovementsCollection;

class UseCase
{
    /** @var MovementsCollection */
    private $movementsCollection;
    /** @var PeriodicalMovementCollection */
    private $periodicalMovementsCollection;
    /** @var \DateTimeInterface */
    private $timeStamp;

    public function __construct(
        MovementsCollection $movementsCollection,
        PeriodicalMovementsCollection $periodicalMovementsCollection,
        \DateTimeInterface $timeStamp
    ) {
        $this->movementsCollection = $movementsCollection;
        $this->periodicalMovementsCollection = $periodicalMovementsCollection;
        $this->timeStamp = $timeStamp;
    }

    public function execute()
    {
        $periodicalMovements = $this->periodicalMovementsCollection->all();
        foreach($periodicalMovements as $periodicalMovement) {
            foreach($periodicalMovement->generateMovements(new \DateTime('2015-08-01') , $this->timeStamp) as $movement) {
                $this->movementsCollection->add($movement);
            }
        }
    }
}