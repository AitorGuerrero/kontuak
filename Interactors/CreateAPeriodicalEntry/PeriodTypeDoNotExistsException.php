<?php

namespace Kontuak\Interactors\CreateAPeriodicalEntry;

use \Exception;

class PeriodTypeDoNotExistsException extends Exception
{
    public function __construct($type)
    {
        parent::__construct(printf('There is not a period type %1', $type));
    }
}