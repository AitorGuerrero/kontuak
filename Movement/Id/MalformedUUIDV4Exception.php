<?php

namespace Kontuak\Movement\Id;

use Kontuak\KontuakException;

class MalformedUUIDV4Exception extends KontuakException
{
    public function __construct($id, $errorMessage)
    {
        parent::__construct("Id $id is malformed. $errorMessage");
    }
}