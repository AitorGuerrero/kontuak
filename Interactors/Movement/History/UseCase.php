<?php

namespace Kontuak\Interactors\Movement\History;

use Kontuak\Interactors\InvalidArgumentException;
use Kontuak\Movement;

class UseCase
{
    /**
     * @var Movement\Source
     */
    private $source;
    /**
     * @var Movement\TotalAmountCalculator
     */
    private $totalAmountService;

    public function __construct(Movement\Source $source, Movement\TotalAmountCalculator $totalAmountService)
    {
        $this->source = $source;
        $this->totalAmountService = $totalAmountService;
    }

    public function execute(Request $request)
    {
        if(gettype($request->limit) !== 'integer') {
            throw new InvalidArgumentException('Required argument "limit"');
        }

        $movements = $this
            ->source
            ->collection()
            ->orderByDateDesc();
        $movementsArray = [];
        for(
            $movement = $movements->current(), $i = 0;
            $movements->valid() && $i < $request->limit;
            $movements->next(), $movement = $movements->current(), $i++
        ) {
            $movementsArray[] = $movement;
        }
        $movementsArray = array_reverse($movementsArray);
        $response = new Response();
        $plainMovements = [];
        $totalAmount = $this->previousTotalAmount(current($movementsArray));
        foreach ($movementsArray as $movement) {
            $totalAmount += $movement->amount();
            $plainMovements[] = [
                'id' => $movement->id()->serialize(),
                'amount' => $movement->amount(),
                'concept' => $movement->concept(),
                'date' => $movement->date()->format('Y-m-d'),
                'totalAmount' => $totalAmount
            ];
        }
        $response->movements = array_reverse($plainMovements);

        return $response;
    }

    private function previousTotalAmount($movement)
    {
        return $this->totalAmountService->getForAMovement($movement);
    }
}