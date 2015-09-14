<?php

namespace Kontuak\UUIDv4;

use Kontuak\KontuakException;

class MalformedUUIDV4Exception extends KontuakException
{
    public function __construct($id, $errorMessage)
    {
        parent::__construct("Id $id is malformed. $errorMessage");
    }
}