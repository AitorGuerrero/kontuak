<?php

namespace Kontuak\Interactors\Movement\Update;

use Kontuak\Interactors\MovementDoesNotExistException;
use Kontuak\Movement;
use Kontuak\Movement\Collection\MovementNotFoundException;

class UseCase
{

    /** @var Movement\Source */
    private $source;

    public function __construct(Movement\Source $source)
    {

        $this->source = $source;
    }

    public function execute(Request $request)
    {
        $movementId = Movement\Id::fromString($request->id);
        try {
            $movement = $this->source->collection()->findById($movementId);
        } catch (MovementNotFoundException $e) {
            throw new MovementDoesNotExistException();
        }
        $movement->updateAmount($request->amount);
        $movement->updateConcept($request->concept);
        $movement->updateDate(new \DateTime($request->date));
        $this->source->persist($movement);
    }
}