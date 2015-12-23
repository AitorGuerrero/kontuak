<?php

namespace Kontuak\Movement;

use Kontuak\Movement;

class History
{
    /** @var Source */
    private $source;
    /** @var TotalAmountCalculator */
    private $totalAmountCalculator;
    /** @var Collection */
    private $collection;

    public function __construct(
        Collection $collection,
        Movement\TotalAmountCalculator $totalAmountCalculator
    ) {
        $this->totalAmountCalculator = $totalAmountCalculator;
        $this->collection = $collection;
    }

    public function toDate(\DateTime $fromDate)
    {
        $movements = $this
            ->source
            ->collection()
            ->filterDateLessOrEqualTo($fromDate)
            ->orderByDate();
        /** @var Movement $movement */
        $totalAmounts = [];
        $firstMovement = $movements->current();
        if(!$firstMovement) {
            return [];
        }
        $totalAmount = $this
            ->totalAmountCalculator
            ->getForAMovement($firstMovement);

        foreach($movements as $movement) {
            $totalAmount += $movement->amount();
            $totalAmounts[] = [
                'totalAmount' => $totalAmount,
                'movement' => $movement
            ];
        }

        return $totalAmounts;
    }

    public function fromDate(\DateTime $toDate, $limit)
    {
        $movements = $this
            ->source
            ->collection()
            ->filterByDateIsPostThan($toDate)
            ->orderByDate();

        /** @var Movement $movement */
        $totalAmounts = [];
        $firstMovement = $movements->current();
        if(!$firstMovement) {
            return [];
        }
        $totalAmount = $this
            ->totalAmountCalculator
            ->getForAMovement($firstMovement);

        $i = 0;
        foreach($movements as $movement) {
            $totalAmount += $movement->amount();
            $totalAmounts[] = [
                'totalAmount' => $totalAmount,
                'movement' => $movement
            ];
            $i++;
            if($i >= $limit) {
                break;
            }
        }

        return $totalAmounts;
    }
}