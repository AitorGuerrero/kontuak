<?php

namespace Kontuak\Interactors\Movement\Remove;

use Kontuak\Interactors\InteractorException;
use Kontuak\Movement;

class MovementDoesNotExistsException extends InteractorException
{
    public function __construct(Movement\Id $movementId)
    {
        $id = $movementId->serialize();
        parent::__construct("Movement with ID '$id' does not exists");
    }
}