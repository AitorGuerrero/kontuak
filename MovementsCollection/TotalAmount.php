<?php

namespace Kontuak\MovementsCollection;

use Kontuak\Movement;

class TotalAmount
{
    /** @var Movement\Source */
    private $source;

    public function __construct(Movement\Source $source)
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