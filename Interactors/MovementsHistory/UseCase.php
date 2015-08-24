<?php

namespace Kontuak\Interactors\MovementsHistory;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\MovementsCollection;
use Kontuak\MovementsCollection\TotalAmount;

class UseCase
{
    /**
     * @var MovementsCollection
     */
    private $collection;
    /**
     * @var TotalAmount
     */
    private $totalAmountService;

    public function __construct(MovementsCollection $collection, TotalAmount $totalAmountService)
    {
        $this->collection = $collection;
        $this->totalAmountService = $totalAmountService;
    }

    public function execute(Request $request)
    {
        if(gettype($request->limit) !== 'integer') {
            throw new InvalidArgumentException();
        }

        $movements = array_reverse(
            $this->collection
                ->orderByDateDesc()
                ->limit($request->limit)
                ->all()
        );
        $response = new Response();
        $plainMovements = [];
        $totalAmount = $this->previousTotalAmount($movements[0]);
        foreach ($movements as $movement) {
            $totalAmount += $movement->amount();
            $plainMovements[] = [
                'id' => $movement->id()->serialize(),
                'amount' => $movement->amount(),
                'concept' => $movement->concept(),
                'date' => $movement->date()->format('Y-m-d'),
                'totalAmount' => $totalAmount
            ];
        }
        $response->movements = $plainMovements;

        return $response;
    }

    private function previousTotalAmount($movement)
    {
        return $this->totalAmountService->getForAMovement($movement);
    }
}