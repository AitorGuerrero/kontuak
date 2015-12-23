<?php

namespace Kontuak\Movement;

use Kontuak\Movement;

class TotalAmountCalculator
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

    /**
     * @param Collection $collection
     * @param null $limit
     * @return array
     */
    public function getForACollection(Collection $collection, $limit = null)
    {
        $collection->orderByDate();
        /** @var Movement $movement */
        $totalAmounts = [];
        $firstMovement = $collection->current();
        if(!$firstMovement) {
            return [];
        }
        $totalAmount = $this->getForAMovement($firstMovement);

        $i = 0;
        foreach($collection as $movement) {
            $i++;
            if(!is_null($limit) && $i > $limit) {
                break;
            }
            $totalAmount += $movement->amount();
            $totalAmounts[] = [
                'totalAmount' => $totalAmount,
                'movement' => $movement
            ];
        }

        return $totalAmounts;
    }
}