<?php

namespace Kontuak\Ports\Movement\Remove;

use Kontuak\Ports\PortException;
use Kontuak\Movement;

class MovementDoesNotExistsException extends PortException
{
    public function __construct(Movement\Id $movementId)
    {
        $id = $movementId->serialize();
        parent::__construct("Movement with ID '$id' does not exists");
    }
}