<?php

namespace Kontuak\Interactors\Movement\Coming;

use Kontuak\Movement\Source;
use Kontuak\Movement\TotalAmountCalculator;

class UseCase
{
    /** @var Source */
    private $movementsSource;
    /** @var \DateTime */
    private $timeStamp;
    /** @var TotalAmountCalculator */
    private $totalAmountCalculator;

    public function __construct(Source $movementsSource, \DateTime $timeStamp, TotalAmountCalculator $totalAmountCalculator)
    {

        $this->movementsSource = $movementsSource;
        $this->timeStamp = $timeStamp;
        $this->totalAmountCalculator = $totalAmountCalculator;
    }

    public function execute()
    {
        $movements = $this
            ->movementsSource
            ->collection()
            ->filterByDateIsPostThan($this->timeStamp)
            ->orderByDateDesc();

        $response = new Response();
        /** @var \Kontuak\Movement $movement */
        foreach($movements as $movement) {
            $response->movements[] = [
                'id' => $movement->id()->serialize(),
                'amount' => $movement->amount(),
                'date' => $movement->date()->format('Y-m-d'),
                'concept' => $movement->concept(),
                'total_amount' => $this->totalAmountCalculator->getForAMovement($movement) + $movement->amount(),
            ];
        }

        return $response;
    }
}