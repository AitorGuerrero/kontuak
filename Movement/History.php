<?php

namespace Kontuak\Movement;

use Kontuak\Movement;

class History 
{
    /** @var Source */
    private $source;
    /** @var TotalAmountCalculator */
    private $totalAmountCalculator;

    public function __construct(
        Movement\Source $source,
        Movement\TotalAmountCalculator $totalAmountCalculator
    ) {

        $this->source = $source;
        $this->totalAmountCalculator = $totalAmountCalculator;
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