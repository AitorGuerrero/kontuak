<?php

namespace Kontuak\Movement\Exception;

use Kontuak\KontuakException;

class InvalidAmount extends KontuakException
{
    protected $message = 'Invalid amount';
}