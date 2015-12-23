<?php

namespace Kontuak\Movement\Exception;

use Kontuak\Exception;

class InvalidAmount extends Exception
{
    protected $message = 'Invalid amount';
}