<?php

namespace Kontuak\Ports\Movement;

use Kontuak\Exception\Source\EntityNotFound;
use Kontuak\Movement;
use Kontuak\Ports\Movement\Remove\MovementDoesNotExistsException;
use Kontuak\Ports\Movement\Remove\Request;

class Remove
{
    /** @var Movement\Source */
    private $source;

    public function __construct(Movement\Source $source)
    {
        $this->source = $source;
    }
    public function execute(Request $request)
    {
        $id = Movement\Id::parse($request->id);
        try {
            $movement = $this->source->get($id);
        } catch (EntityNotFound $e) {
            throw new MovementDoesNotExistsException($id);
        }
        $this->source->remove($movement);
    }
}