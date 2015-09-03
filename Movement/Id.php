<?php

namespace Kontuak\Movement;

class Id
{
    /** @var string */
    private $uniqueId;

    public function __construct()
    {
        $this->uniqueId = uniqid();
    }

    public function serialize()
    {
        return $this->uniqueId;
    }

    public static function fromString($string)
    {
        $id = new self();
        $id->uniqueId = $string;
        return $id;
    }
}