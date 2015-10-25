<?php

namespace Kontuak\Ports\Exception;

class InvalidArgument extends PortException
{
    public function __construct($argumentName)
    {
        parent::__construct("Invalid argument $argumentName");
    }
}