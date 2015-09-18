<?php

namespace Kontuak\Interactors\Exception;

use Kontuak\KontuakException;

class InvalidArgument extends KontuakException
{
    public function __construct($argumentName)
    {
        parent::__construct("Invalid argument $argumentName");
    }
}