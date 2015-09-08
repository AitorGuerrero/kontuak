<?php

namespace Kontuak\Interactors\Movement\GetOne;

use Kontuak\Movement;

class UseCase
{

    /** @var Movement\Source */
    private $source;

    public function __construct(Movement\Source $source)
    {

        $this->source = $source;
    }

    public function execute(Request $response)
    {
        try {
            $movement = $this
                ->source
                ->collection()
                ->findById(Movement\Id::fromString($response->id));
        } catch (Movement\Collection\MovementNotFoundException $e) {
            throw new MovementNotFoundException();
        }

        $response = new Response();
        $response->movement = [
            'id' => $movement->id()->serialize(),
            'amount' => $movement->amount(),
            'date' => $movement->date()->format('Y-m-d'),
            'concept' => $movement->concept(),
        ];
        return $response;
    }
}