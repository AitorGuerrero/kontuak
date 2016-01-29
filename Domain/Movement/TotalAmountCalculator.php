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
     * @return Transaction[]
     */
    public function getForACollection(Collection $collection, $limit = null)
    {
        $collection = $collection->orderByDate();
        /** @var Movement $movement */
        $transactions = [];
        $collection->rewind();
        $firstMovement = $collection->current();
        if(!$firstMovement) {
            return [];
        }
        $totalAmount = $this->getForAMovement($firstMovement);
        if (!is_null($limit)) {
            for ($i = 0; $i < $limit; $i++) {
                if (!$collection->valid()) {
                    break;
                }
                $movement = $collection->current();
                $totalAmount += $movement->amount();
                $transactions[] = new Transaction($movement, $totalAmount);
                $collection->next();
            }
        } else {
            foreach($collection as $movement) {
                $totalAmount += $movement->amount();
                $transactions[] = new Transaction($movement, $totalAmount);
            }
        }

        return array_reverse($transactions);
    }
}