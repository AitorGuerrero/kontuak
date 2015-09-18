<?php

namespace Kontuak\Interactors\Movement\Coming;

use Kontuak\Movement\Source;
use Kontuak\Movement\TotalAmountCalculator;
use Kontuak\Movement\Transformer;

class UseCase
{
    /** @var Source */
    private $movementsSource;
    /** @var \DateTime */
    private $timeStamp;
    /** @var TotalAmountCalculator */
    private $totalAmountCalculator;
    /** @var Transformer */
    private $movementTransformer;

    public function __construct(
        Source $movementsSource,
        \DateTime $timeStamp,
        TotalAmountCalculator $totalAmountCalculator,
        Transformer $movementTransformer
    ) {

        $this->movementsSource = $movementsSource;
        $this->timeStamp = $timeStamp;
        $this->totalAmountCalculator = $totalAmountCalculator;
        $this->movementTransformer = $movementTransformer;
    }

    public function execute(Request $request)
    {
        $movements = $this
            ->movementsSource
            ->collection()
            ->filterByDateIsPostThan($this->timeStamp)
            ->orderByDateDesc();

        $response = new Response();
        /** @var \Kontuak\Movement $movement */
        $amount = 0;
        foreach($movements as $movement) {
            $response->movements[] = [
                'total_amount' => $this->totalAmountCalculator->getForAMovement($movement) + $movement->amount(),
                'movement' => $this->movementTransformer->toResource($movement),
            ];
            $amount++;
            if($amount > $request->limit) {
                break;
            }
        }

        return $response;
    }
}