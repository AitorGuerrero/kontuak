<?php

namespace Kontuak\Interactors\Movement\Remove;

use Kontuak\Exception\Source\EntityNotFound;
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
        $id = new Movement\Id($request->id);
        try {
            $movement = $this->source->get($id);
        } catch (EntityNotFound $e) {
            throw new MovementDoesNotExistsException($id);
        }
        $this->source->remove($movement);
    }
}