<?php

namespace Kontuak\MovementsCollection;

use Kontuak\Movement;
use Kontuak\MovementsSource;

class TotalAmount
{
    /** @var MovementsSource */
    private $source;

    public function __construct(MovementsSource $source)
    {
        $this->source = $source;
    }

    public function getForAMovement(Movement $movement)
    {
        $previousTotalAmount = $this->source
            ->collection()
            ->filterDateLessThan($movement->date())
            ->amountSum();
        $dateAmount = $this->source
            ->collection()
            ->filterByDateIs($movement->date())
            ->filterByCreatedIsLessThan($movement->created())
            ->amountSum();

        return $previousTotalAmount + $dateAmount;
    }
}