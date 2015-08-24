<?php
/**
 * Created by PhpStorm.
 * User: aitor.guerrero
 * Date: 22/8/15
 * Time: 14:45
 */

namespace Kontuak\MovementsCollection;


use Kontuak\Movement;
use Kontuak\MovementsCollection;

class TotalAmount
{
    /** @var MovementsCollection */
    private $collection;

    public function __construct(MovementsCollection $collection)
    {
        $this->collection = $collection;
    }

    public function getForAMovement(Movement $movement)
    {
        $previousTotalAmount = $this->collection
            ->filterDateLessThan($movement->date())
            ->amountSum();
        $dateAmount = $this->collection
            ->filterByDateIs($movement->date())
            ->filterByCreatedIsLessThan($movement->created())
            ->amountSum();

        return $previousTotalAmount + $dateAmount;
    }
}