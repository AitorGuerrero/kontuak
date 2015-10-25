<?php

namespace Kontuak\Ports\Movement\Remove;

use Kontuak\Ports\Exception\PortException;
use Kontuak\Movement;

class MovementDoesNotExistsException extends PortException
{
    public function __construct(Movement\Id $movementId)
    {
        $id = $movementId->toString();
        parent::__construct("Movement with ID '$id' does not exists");
    }
}