<?php

namespace Kontuak\Interactors\Movement\Coming;

use Kontuak\Movement\Source;

class UseCase
{
    /** @var Source */
    private $movementsSource;
    /** @var \DateTime */
    private $timeStamp;

    public function __construct(Source $movementsSource, \DateTime $timeStamp)
    {

        $this->movementsSource = $movementsSource;
        $this->timeStamp = $timeStamp;
    }

    public function execute()
    {
        $movements = $this
            ->movementsSource
            ->collection()
            ->filterByDateIsPostThan($this->timeStamp)
            ->orderByDate();
        $response = new Response();
        /** @var \Kontuak\Movement $movement */
        foreach($movements as $movement) {
            $response->movements[] = [
                'id' => $movement->id()->serialize(),
                'amount' => $movement->amount(),
                'date' => $movement->date()->format('Y-m-d'),
                'concept' => $movement->concept(),
            ];
        }

        return $response;
    }
}