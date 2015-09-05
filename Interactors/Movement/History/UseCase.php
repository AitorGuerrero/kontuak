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
    /** @var \DateTime */
    private $today;

    public function __construct(
        Movement\Source $source,
        Movement\TotalAmountCalculator $totalAmountService,
        \DateTime $today = null
    ) {
        $this->source = $source;
        $this->totalAmountService = $totalAmountService;
        $this->today = null !== $today ? $today : new \DateTime();
    }

    public function execute(Request $request)
    {
        $this->assertRequest($request);
        $response = new Response();
        $movements = $this->movements();
        if($movements->count() > 0) {
            $response->movements = $this->processMovementsToOutput($movements, $request->limit);
        } else {
            $response->movements = [];
        }

        return $response;
    }

    private function previousTotalAmount($movement)
    {
        return $this->totalAmountService->getForAMovement($movement);
    }

    /**
     * @param $limit
     * @param $movements
     * @return array
     */
    private function collectionToArray($movements, $limit)
    {
        $movementsArray = [];
        for (
            $movement = $movements->current(), $i = 0;
            $movements->valid() && $i < $limit;
            $movements->next(), $movement = $movements->current(), $i++
        ) {
            $movementsArray[] = $movement;
        }
        return $movementsArray;
    }

    /**
     * @param $movementsArray
     * @return array
     */
    private function movementsToPlain($movementsArray)
    {
        $plainMovements = [];
        $totalAmount = $this->previousTotalAmount(current($movementsArray));
        foreach ($movementsArray as $movement) {
            $totalAmount += $movement->amount();
            $plainMovements[] = [
                'id' => $movement->id()->serialize(),
                'amount' => $movement->amount(),
                'concept' => $movement->concept(),
                'date' => $movement->date()->format('Y-m-d'),
                'created' => $movement->created()->format('Y-m-d h:i:s'),
                'totalAmount' => $totalAmount
            ];
        }
        return $plainMovements;
    }

    /**
     * @param $movements
     * @param $limit
     * @return array
     */
    private function processMovementsToOutput($movements, $limit)
    {
        $movementsArray = $this->collectionToArray($movements, $limit);
        $movementsArray = array_reverse($movementsArray);
        $plainMovements = $this->movementsToPlain($movementsArray);
        $plainMovements = array_reverse($plainMovements);
        return $plainMovements;
    }

    /**
     * @return Movement\Collection
     */
    private function movements()
    {
        $movements = $this
            ->source
            ->collection()
            ->filterDateLessOrEqualTo($this->today)
            ->orderByDateDesc();
        return $movements;
    }

    /**
     * @param Request $request
     * @throws InvalidArgumentException
     */
    private function assertRequest(Request $request)
    {
        if (gettype($request->limit) !== 'integer') {
            throw new InvalidArgumentException('Required argument "limit"');
        }
    }
}