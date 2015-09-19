<?php

namespace Kontuak\Interactors\Movement\Update;

use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\Interactors\MovementDoesNotExistException;
use Kontuak\Movement;

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
        $movementId = new Movement\Id($request->id);
        try {
            $movement = $this->source->get($movementId);
        } catch (EntityNotFound $e) {
            throw new MovementDoesNotExistException();
        }
        $movement->updateAmount($request->amount);
        $movement->updateConcept($request->concept);
        $movement->updateDate(new \DateTime($request->date));
        $this->source->persist($movement);
    }
}